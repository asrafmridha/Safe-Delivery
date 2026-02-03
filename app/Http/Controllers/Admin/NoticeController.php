<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Matcher\Not;
use Yajra\DataTables\DataTables;

class NoticeController extends Controller
{
    public function index() {
        $data               = [];
        $data['main_menu']  = 'notice';
        $data['child_menu'] = 'noticeList';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Notice List';
        return view('admin.notice.index', $data);
    }

    public function getNoticeList(Request $request) {
        $model = Notice::orderBy('id', 'DESC')->get();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('type', function ($data) {

                switch ($data->type) {
                    case 1:
                        $type = "Notice";
                        break;
                    case 2:
                        $type = "News";
                        break;
                    default:
                        $type = "N/A";
                        break;
                }

                return $type;
            })
            ->editColumn('publish_for', function ($data) {

                switch ($data->publish_for) {
                    case 1:
                        $type = "Branch";
                        break;
                    case 2:
                        $type = "Merchant";
                        break;
                    default:
                        $type = "All";
                        break;
                }

                return $type;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" notice_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" notice_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('admin.notice.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>&nbsp;&nbsp;';
                $button .= '<button class="btn btn-danger btn-sm delete-btn" notice_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['type', 'status', 'action'])
            ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'notice';
        $data['child_menu'] = 'noticeList';
        $data['page_title'] = 'Create New NoticeOrNews';
        return view('admin.notice.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required',
            'short_details'         => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'title'             => $request->input('title'),
            'short_details'     => $request->input('short_details'),
            'type'              => 2,
            'publish_for'       => $request->input('publish_for'),
            'date_time'         => date("Y-m-d H:i:s"),
            'user_id'           => auth()->guard('admin')->user()->id,
        ];
        $check = Notice::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Create Successfully', 'success');
            return redirect()->route('admin.notice.index');
        } else {
            $this->setMessage('Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, Notice $notice) {

        return view('admin.notice.show', compact('notice'));
    }

    public function edit(Request $request, Notice $notice) {
        $data               = [];
        $data['main_menu']  = 'notice';
        $data['child_menu'] = 'noticeList';
        $data['page_title'] = 'Edit NoticeOrNews';
        $data['notice'] = $notice;
        return view('admin.notice.edit', $data);
    }

    public function update(Request $request, Notice $notice) {
        $validator = Validator::make($request->all(), [
            'title'          => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'title'             => $request->input('title'),
            'short_details'     => $request->input('short_details'),
            'type'              => 2,
            'publish_for'       => $request->input('publish_for'),
            'date_time'         => date("Y-m-d H:i:s"),
            'user_id'           => auth()->guard('admin')->user()->id,
        ];

        $check = notice::where('id', $notice->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Update Successfully', 'success');
            return redirect()->route('admin.notice.index');
        } else {
            $this->setMessage('Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'notice_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Notice::where('id', $request->notice_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Update Successfully',
                        'status'  => $request->status,
                    ];
                } else {
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }

            }

        }

        return response()->json($response);
    }

    public function destroy(Request $request, Notice $notice) {
        $check = $notice->delete() ? true : false;

        if ($check) {
            $this->setMessage('Delete Successfully', 'success');
        } else {
            $this->setMessage('Delete Failed', 'danger');
        }

        return redirect()->route('admin.notice.index');
    }

    public function delete(Request $request) {
        // dd($request->all());
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'notice_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $notice = Notice::where('id', $request->notice_id)->first();
                $check  = Notice::where('id', $request->notice_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


}
