<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Aggregates\Assets\FileUploadAggregate;
use App\Exceptions\Assets\UploadedFileException;
use App\Http\Requests\Assets\SourceCodeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Ramsey\Uuid\Uuid;

/**
 * Class SourceCodeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SourceCodeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Assets\SourceCodeUpload::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/assets/source-code');
        CRUD::setEntityNameStrings('source code', 'source codes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::column('file_nickname')->type('text');
        CRUD::column('active')->type('boolean');
        CRUD::column('description')->type('text');



        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SourceCodeRequest::class);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */

        CRUD::field('file_nickname')->type('text');
        CRUD::field('file_path')->type('text')->hint('Will save to /candidate_assessment/source_code in the cloud');
        CRUD::field('description')->type('textarea');
    }

    public function store()
    {
        $data = request()->all();
        $uploaded_file_id = Uuid::uuid4()->toString();
        $source_code_id   = Uuid::uuid4()->toString();

        try {
            FileUploadAggregate::retrieve($uploaded_file_id)
                ->createNewSourceCodeFile($data['file_nickname'], $data['file_path'], $data['description']?? '', $source_code_id)
                ->persist();

            \Alert::add('success','File Records Created!.')->flash();
            $this->crud->setSaveAction();
            return $this->crud->performSaveAction($source_code_id);
        }
        catch(UploadedFileException $e)
        {
            \Alert::add('error', $e->getMessage())->flash();
        }

        $this->crud->setSaveAction();
        return redirect()->back();

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if(backpack_user()->cannot('create_assessments'))
        {
            $this->crud->hasAccessOrFail('nope');

        }

        $entry = $this->crud->getCurrentEntry();
        $this->setupCreateOperation();
        $aggy = FileUploadAggregate::retrieve($entry->file_id);

        CRUD::field('file_nickname')->type('text');
        CRUD::field('file_path')->type('text')->hint('Will save to /candidate_assessment/source_code in the cloud')
            ->value($aggy->getFilePath());
        CRUD::field('description')->type('textarea');
    }
}
