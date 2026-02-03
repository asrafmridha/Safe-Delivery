<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;
use App\Models\FrequentlyAskQuestion;


class FrequentlyAskQuestionController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'frequentlyAskQuestion';
        $data['page_title'] = 'Frequently Ask Questions';
        return view('admin.website.frequentlyAskQuestion.index', $data);
    }

    public function getFrequentlyAskQuestions (Request $request){
        $model  = FrequentlyAskQuestion::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" frequently_ask_question_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" frequently_ask_question_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.frequentlyAskQuestion.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" frequently_ask_question_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'frequentlyAskQuestion';
        $data['page_title'] = 'Create Frequently Ask Question';
        return view('admin.website.frequentlyAskQuestion.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'question'      => 'required',
            'answer'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $data = [
            'question'          => $request->input('question'),
            'answer'            => $request->input('answer'),
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = FrequentlyAskQuestion::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Frequently Ask Question Create Successfully', 'success');
            return redirect()->route('admin.frequentlyAskQuestion.index');
        } else {
            $this->setMessage('Frequently Ask Question Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, FrequentlyAskQuestion $frequentlyAskQuestion) {
        return view('admin.website.frequentlyAskQuestion.show', compact('frequentlyAskQuestion'));
    }

    public function edit(Request $request, FrequentlyAskQuestion $frequentlyAskQuestion) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'frequentlyAskQuestion';
        $data['page_title'] = 'Edit Frequently Ask Question';
        $data['frequentlyAskQuestion'] = $frequentlyAskQuestion;
        return view('admin.website.frequentlyAskQuestion.edit', $data);
    }

    public function update(Request $request, FrequentlyAskQuestion $frequentlyAskQuestion) {
        $validator = Validator::make($request->all(), [
            'question'      => 'required',
            'answer'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'question'          => $request->input('question'),
            'answer'            => $request->input('answer'),
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = FrequentlyAskQuestion::where('id', $frequentlyAskQuestion->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Frequently Ask Question Update Successfully', 'success');
            return redirect()->route('admin.frequentlyAskQuestion.index');
        } else {
            $this->setMessage('Frequently Ask Question Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'frequently_ask_question_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = FrequentlyAskQuestion::where('id', $request->frequently_ask_question_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Frequently Ask Question Status Update Successfully',
                        'status'  => $request->status,
                    ];
                }
                else{
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }
        return response()->json($response);
    }

    public function destroy(Request $request, FrequentlyAskQuestion $frequentlyAskQuestion) {

        $check = $frequentlyAskQuestion->delete() ? true : false;

        if ($check) {
            $this->setMessage('Frequently Ask Question Delete Successfully', 'success');
        } else {
            $this->setMessage('Team  Member Delete Failed', 'danger');
        }
        return redirect()->route('admin.frequentlyAskQuestion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'frequently_ask_question_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check = FrequentlyAskQuestion::where('id', $request->frequently_ask_question_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Frequently Ask Question Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}
