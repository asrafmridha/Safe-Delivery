<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ApplicationController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data                = [];
        $data['main_menu']   = 'setting';
        $data['child_menu']  = 'application';
        $data['page_title']  = 'Application ';
        return view('admin.setting.application.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $application = Application::with('admin')->find($id);
        return view('admin.setting.application.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application) {

        $data                = [];
        $data['main_menu']   = 'setting';
        $data['child_menu']  = 'application';
        $data['page_title']  = 'Edit Application ';
        $data['application'] = $application;
        return view('admin.setting.application.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application) {



        $validator = Validator::make($request->all(), [
            'name'           => 'required|min:3',
            'email'          => 'required',
            'contact_number' => 'required',
            // 'photo'          => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // if($request->hasFile('photo')){ 
        //     $path = public_path() . "/uploads/application/" . $application->photo; 
        //     if (file_exists($path)) { 
        //         unlink($path); 
        //     } 

        //     $file = $request->file('photo'); 
        //     $imageEncName = rand(0,9999).md5($file->getClientOriginalName()); 
        //     $imageExtension = $file->getClientOriginalExtension(); 
        //     $photo_name = $imageEncName.".".$imageExtension; 
        //     $destinationPath = "uploads/application";
        //     $file->move($destinationPath, $photo_name); 
        // }else{ 
        //     $photo_name=$application->photo;
        // }
        
        $photo_name = $application->photo;

        if ($request->hasFile('photo')) {
            $photo      = $request->file('photo');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/application/' . $photo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($application->photo)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/application/' . $application->photo;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        
        $logo_name = $application->logo;

        if ($request->hasFile('logo')) {
            $photo      = $request->file('logo');
            $logo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/application/' . $logo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($application->logo)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/application/' . $application->logo;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        
        
        
        $og_name = $application->og_image;

        if ($request->hasFile('og_image')) {
            $photo      = $request->file('og_image');
            $og_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/application/' . $og_name;
            Image::make($photo)->save($photo_path);

            if (!empty($application->og_image)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/application/' . $application->og_image;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        
        
        
        
         
        $favicon_name = $application->favicon;

        if ($request->hasFile('favicon')) {
            $photo      = $request->file('favicon');
            $favicon_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/application/' . $favicon_name;
            Image::make($photo)->save($photo_path);

            if (!empty($application->favicon)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/application/' . $application->favicon;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        
        
        
        
        
        
        
        

        // $logo_name = $application->logo;
        // if ($request->hasFile('logo')) {
        //     // $this->removeFile($application->logo, 'public/uploads/application/');
        //     $logo_name = $this->uploadFile($request->file('logo'), 'application/');
            
        // }

        $data = [
            'name'             => $request->input('name'),
            'email'            => $request->input('email'),
            'contact_number'   => $request->input('contact_number'),
            'address'          => $request->input('address'),
            'photo'            => $photo_name,
            'og_image'         => $og_name,
            'favicon'          => $favicon_name,
            'logo'             => $logo_name,
            'app_link'         => $request->input('app_link'),
            'meta_author'      => $request->input('meta_author'),
            'meta_keywords'    => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'google_map'       => $request->input('google_map'),
            'admin_id'         => auth()->guard('admin')->user()->id,
        ];
        
        // dd($data);
        
        

        $session_data = [
            'company_name'           => $data['name'],
            'company_email'          => $data['email'],
            'company_address'        => $data['address'],
            'company_contact_number' => $data['contact_number'],
            'company_photo'          => $data['photo'],
        ];
        session()->put($session_data);

        $check = Application::where('id', $application->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Application Update Successfully', 'success');
            return redirect()->route('admin.application.index');
        } else {
            $this->setMessage('Application Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
