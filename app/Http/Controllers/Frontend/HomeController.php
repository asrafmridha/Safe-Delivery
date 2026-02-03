<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutPoint;
use App\Models\Area;
use App\Models\Blog;
use App\Models\Content;
use App\Models\CustomerFeedback;
use App\Models\DeliveryService;
use App\Models\District;
use App\Models\Feature;
use App\Models\FrequentlyAskQuestion;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\NewsLetter;
use App\Models\Objective;
use App\Models\Office;
use App\Models\PageContent;
use App\Models\ParcelStep;
use App\Models\Partner;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\ServiceType;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\VisitorMessage;
use App\Models\WeightPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Parcel;
use App\Models\Branch;
use App\Models\ParcelLog;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function index()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['objectives'] = Objective::where([['status', '=', '1']])->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['deliveryServices'] = DeliveryService::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['blogs'] = Blog::where('status', 1)->take(4)->get();
        $data['features'] = Feature::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['serviceAreas'] = ServiceArea::with(['weight_packages'])->where('status', 1)->get();
        $data['weightPackages'] = WeightPackage::where('status', 1)->get();
        $data['teamMembers'] = TeamMember::where([['status', '=', '1']])->get();

        $data['become_merchant'] = Content::where('content_type', 'become_merchant')->first();
        $data['become_franchisee'] = Content::where('content_type', 'become_franchisee')->first();

        $data['total_parcels'] = Parcel::where([['status', '!=', 3]])->count();
        $data['total_branches'] = Branch::where([['status', '=', 1]])->count();
        $data['total_districts'] = District::where([['status', '=', 1]])->count();

        // dd($data['total_parcels']);

        return view('frontend.home', $data);
    }
    
    public function test_sms()
    {
        $company_name='Humayun';
        $otp_token='128759';
         $message = "Dear {$company_name}, ";
                $message .= "Your OTP is {$otp_token} From STITBD SMS Test. Please Confirm your account and keep it secret";
     $res = $this->send_sms("01852148425", $message);
    //  $res = send_bl_sms("01609550979", "test");

    // Dump or log the response for inspection
    dd($res->json()); // Dumps the JSON response to the browser
    }

    public function about()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['blogs'] = Blog::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        return view('frontend.about', $data);
    }

    public function teamMember()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['blogs'] = Blog::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();
        return view('frontend.teamMember', $data);
    }

    public function quotation()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['blogs'] = Blog::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();
        return view('frontend.quotation', $data);
    }

    public function faq()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['frequentlyAskQuestions'] = FrequentlyAskQuestion::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();
        return view('frontend.faq', $data);
    }

    public function services()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        // $data['services']               = Service::where([['status', '=', '1']])->paginate(3);
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['deliveryServices'] = DeliveryService::where([['status', '=', '1']])->get();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();
        return view('frontend.services', $data);
    }

    public function serviceDetails(Request $request, $slug)
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['service'] = Service::where([['slug', '=', $slug]])->first();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();
        return view('frontend.serviceDetails', $data);
    }

    public function delivery()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['teamMembers'] = TeamMember::with('designation')->where('status', 1)->get();

        $data['weightPackages'] = WeightPackage::where([
            ['status', '=', 1],
            ['weight_type', '=', 1],
        ])->get();
        $data['areas'] = Area::with('upazila')->where('status', 1)->get();
        return view('frontend.delivery', $data);
    }

    public function blogs()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['blogs'] = Blog::where('status', 1)->paginate(2);
        return view('frontend.blogs', $data);
    }

    public function blogDetails(Request $request, $slug)
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['blogs'] = Blog::where('status', 1)->orderBy('id', 'desc')->take(4)->get();
        $data['blog'] = Blog::where([['slug', '=', $slug]])->first();
        return view('frontend.blogDetails', $data);
    }

    public function contact()
    {
        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['servicePage'] = PageContent::where('page_type', 2)->first();
        $data['offices'] = Office::where('status', 1)->get();
        return view('frontend.contact', $data);
    }

    public function visitorMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ];
        $check = VisitorMessage::create($data) ? true : false;

        if ($check) {
            return response()->json(['success' => "Message Send Successfully.."]);
        } else {
            return response()->json(['error' => "Message Send Successfully.."]);
        }

    }

    public function newsLetter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = [
            'email' => $request->input('email'),
        ];
        $check = NewsLetter::create($data) ? true : false;

        if ($check) {
            return response()->json(['success' => "Subscribe Email Successfully.."]);
        } else {
            return response()->json(['error' => "Subscribe Email Successfully.."]);
        }

    }

    public function orderTracking(Request $request)
    {

        $data = [];
        $data['sliders'] = Slider::where('status', 1)->get();
        $data['parcelSteps'] = ParcelStep::where('status', 1)->get();
        $data['aboutPoints'] = AboutPoint::where('status', 1)->get();
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['blogs'] = Blog::where('status', 1)->get();
        $data['parcel'] = [];
        $data['trackingBox'] = $request->input('trackingBox')??'';
        $trackingOrder = $request->input('trackingBox');

        if (!is_null($trackingOrder) && $trackingOrder != '') {
            $parcel = Parcel::with('district', 'upazila', 'area', 'merchant',
                'weight_package', 'pickup_branch', 'pickup_rider',
                'delivery_branch', 'delivery_rider')
                ->where(function ($query) use ($trackingOrder) {
                    $query->where('parcel_invoice', 'like', "%$trackingOrder");
                    $query->orWhere('merchant_order_id', 'like', "%$trackingOrder");
                })
                ->first();


            if ($parcel) {
                $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch',
                    'delivery_rider', 'admin', 'merchant')
                    ->where('parcel_id', $parcel->id)
                    ->orderBy('id', 'desc')
                    ->get();

                $data['parcel'] = $parcel;
                $data['parcelLogs'] = $parcelLogs;
            }
        }
//dd($parcelLogs);
        return view('frontend.orderTracking', $data);
    }

    public function merchantRegistration()
    {
        $data = [];
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['districts'] = District::where([['status', '=', '1']])->orderBy("name", "asc")->get();
        $data['merchantRegistrationPage'] = PageContent::where('page_type', 3)->first();
        return view('frontend.merchantRegistration', $data);
    }

    public function privacypolicy()
    {

    }


    public function getPrivacyPolicy()
    {
        $data = [];
        $data['partners'] = Partner::where('status', 1)->get();
        $data['services'] = Service::where([['status', '=', '1'], ['icon', '!=', null]])->get();
        $data['customerFeedbacks'] = CustomerFeedback::where('status', 1)->get();
        $data['aboutPage'] = PageContent::where('page_type', 1)->first();
        $data['services'] = Service::where([['status', '=', '1']])->get();
        $data['districts'] = District::where([['status', '=', '1']])->orderBy("name", "asc")->get();
        $data['privacyPolicyPage'] = PageContent::where('page_type', 4)->first();

        return view('frontend.privacypolicyPage', $data);
    }

    public function returnWeightPackageOptionAndCharge(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'service_area_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $charge = 0;
                $code_charge_percent = 0;
                $serviceTypeOption = '<option value="0" data-charge="0">Select Service Type</option>';
                $itemTypeOption = '<option value="0" data-charge="0">Select Item Type</option>';
                $weightPackageOption = '<option value="0" data-charge="0">Select Weight Package </option>';
                $serviceArea = ServiceArea::where('id', $request->input('service_area_id'))->first();

                if (!empty($serviceArea)) {
                    $charge = $serviceArea->default_charge;
//                    $charge = null;
                    if (!$charge) {
                        $charge = 60;
                    }

                    $service_area_id = $request->input('service_area_id');
                    $weightPackages = WeightPackage::with([
                        'service_area' => function ($query) use ($service_area_id) {
                            $query->where('service_area_id', '=', $service_area_id);
                        },
                    ])
                        ->where(['status' => 1])
                        ->orderBy('weight_type', 'asc')
                        ->get();

                    foreach ($weightPackages as $weightPackage) {
                        $rate = $weightPackage->rate;
                        if (!empty($weightPackage->service_area)) {
                            $rate = $weightPackage->service_area->rate;
                        }
                        $weightPackageOption .= '<option  value="' . $weightPackage->id . '" data-charge="' . $rate . '"> ' . $weightPackage->name . ' </option>';
                    }

                    $serviceTypes = ServiceType::where('service_area_id', $service_area_id)->get();
                    foreach ($serviceTypes as $serviceType) {
                        $serviceTypeOption .= '<option value="' . $serviceType->id . '" data-charge="' . $serviceType->rate . '">' . $serviceType->title . '</option>';
                    }
                    $itemTypes = ItemType::where('service_area_id', $service_area_id)->get();
                    foreach ($itemTypes as $itemType) {
                        $itemTypeOption .= '<option value="' . $itemType->id . '" data-charge="' . $itemType->rate . '">' . $itemType->title . '</option>';
                    }
                    $code_charge_percent = $serviceArea->cod_charge;
                }

                $response = [
                    'success' => 1,
                    'weightPackageOption' => $weightPackageOption,
                    'charge' => $charge,
                    'cod_charge' => $code_charge_percent,
                    'serviceTypeOption' => $serviceTypeOption,
                    'itemTypeOption' => $itemTypeOption,
                ];
            }

        }

        return response()->json($response);
    }


}
