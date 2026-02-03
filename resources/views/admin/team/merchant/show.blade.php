  <div class="row">
    <table class="table table-sm">
        @if(!empty($merchant->image))
        <tr>
            <td colspan="2" class="text-center">
                <img src="{{ asset('uploads/merchant/'.$merchant->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant User">
            </td>
        </tr>
        @endif
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $merchant->status == 1 ? "success":"danger" }}">{{ $merchant->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Company </th>
          <td width="70%">
            {{ $merchant->company_name }}
          </td>
        </tr>
        <tr>
          <th>Name</th>
          <td>
            {{ $merchant->name }}
          </td>
        </tr>
        <tr>
          <th>Merchant ID</th>
          <td>
            {{ $merchant->m_id }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $merchant->address }}
          </td>
        </tr>
        <tr>
            <th>Business Address</th>
            <td>
              {{ $merchant->business_address }}
            </td>
          </tr>
        <tr>
          <th>District</th>
          <td>
            {{ $merchant->district->name }}
          </td>
        </tr>
        {{-- <tr>
          <th>Thana/Upazila</th>
          <td>
            {{ $merchant->upazila->name }}
          </td>
        </tr> --}}
        <tr>
          <th>Area</th>
          <td>
            {{ $merchant->area->name }}
          </td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td>
            {{ $merchant->contact_number }}
            @php
                if($merchant->otp_token_status == 1) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Verified</b></span>";
                }else{
                    echo " <span class='bg-warning' style='padding: 0 5px; border-radius: 4px;'><b>Not Verified</b></span>";
                }
            @endphp
          </td>
        </tr>
        
         <tr>
          <th>Payment Recived By</th>
          <td>
            
            @php
                if($merchant->payment_recived_by== 1) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Cash</b></span>";
                }if($merchant->payment_recived_by== 2) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Bkash</b></span>";
                }if($merchant->payment_recived_by== 3) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Nagad</b></span>";
                }if($merchant->payment_recived_by== 4) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Rocket</b></span>";
                }if($merchant->payment_recived_by== 5) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Bank</b></span>";
                }if($merchant->payment_recived_by== 0){
                    echo " <span class='bg-warning' style='padding: 0 5px; border-radius: 4px;'><b>Not Selected</b></span>";
                }
            @endphp
          </td>
        </tr>
        <tr>
            <th>Branch</th>
            <td>
              {{ $merchant->branch->name ?? "" }}
            </td>
          </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $merchant->email }}

              @php
                  if(!is_null($merchant->email_verified_at)) {
                      echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Verified</b></span>";
                  }else{
                      echo " <span class='bg-warning' style='padding: 0 5px; border-radius: 4px;'><b>Not Verified</b></span>";
                  }
              @endphp
          </td>
        </tr>
        <!--<tr>-->
        <!--  <th>Password</th>-->
        <!--  <td>-->
        <!--    {{ $merchant->store_password }}-->
        <!--  </td>-->
        <!--</tr>-->

        @if(!empty($merchant->service_area_cod_charges->count() > 0))
        <tr>
            <th>Service Area Merchant COD Charge </th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="45%">Service Area</th>
                      <th width="45%">COD Charge</td>
                    </tr>
                    @foreach ($merchant->service_area_cod_charges as $service_cod_charge)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $service_cod_charge->name }} </th>
                      <td >{{ $service_cod_charge->pivot->cod_charge }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif

        @if(!empty($merchant->service_area_charges->count() > 0))
        <tr>
            <th>Service Area Merchant Delivery Charge </th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="45%">Service Area</th>
                      <th width="45%">Charge</td>
                    </tr>
                    @foreach ($merchant->service_area_charges as $service_area_charge)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $service_area_charge->name }} </th>
                      <td >{{ floatval($service_area_charge->pivot->charge) }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        @else
            <tr>
                <th>Service Area Default Delivery Charge</th>
                <td>
                    <table class="table table-sm">
                        <tr>
                            <th width="10%">#</th>
                            <th width="45%">Service Area</th>
                            <th width="45%">Charge</td>
                        </tr>
                        @foreach ($serviceAreas as $service_area)
                            <tr>
                                <td >{{ $loop->iteration }} </th>
                                <td >{{ $service_area->name }} </th>
                                <td >{{ floatval($service_area->default_charge) }} </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif

        @if(!empty($merchant->service_area_return_charges->count() > 0))
        <tr>
            <th>Service Area Return Charge </th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="45%">Service Area</th>
                      <th width="45%">Charge</td>
                    </tr>
                    @foreach ($merchant->service_area_return_charges as $service_area_return_charge)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $service_area_return_charge->name }} </th>
                      <td >{{ floatval($service_area_return_charge->pivot->return_charge) }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif

        <tr>
            <th>FB URL</th>
            <td>
              {{ $merchant->fb_url }}
            </td>
        </tr>
        <tr>
            <th>Web Site</th>
            <td>
              {{ $merchant->web_url }}
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table class="table table-sm">
                    <tr>
                      <th width="20%" class="text-center">Bank Account Name </th>
                      <th width="20%" class="text-center">Bank Account Number </th>
                      <th width="20%" class="text-center">Bank Name </th>
                       <th width="20%" class="text-center">Branch Name </th>
                        <th width="20%" class="text-center">Route No </th>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $merchant->bank_account_name ?? 'NON' }} </td>
                        <td class="text-center">{{ $merchant->bank_account_no ?? 'NON' }} </td>
                        <td class="text-center">{{ $merchant->bank_name  ?? 'NON'}} </td>
                         <td class="text-center">{{ $merchant->bank_branch_name  ?? 'NON'}} </td>
                          <td class="text-center">{{ $merchant->bank_route_no  ?? 'NON'}} </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table class="table table-sm">
                    <tr>
                      <th width="33%" class="text-center">BKash Number</th>
                      <th width="33%" class="text-center">Nagad Number</th>
                      <th width="33%" class="text-center">Rocket Number </th>
                    </tr>
                    <tr>
                        <td class="text-center">{{ $merchant->bkash_number ?? 'NON' }} </td>
                        <td class="text-center">{{ $merchant->nagad_number ?? 'NON' }} </td>
                        <td class="text-center">{{ $merchant->rocket_name  ?? 'NON'}} </td>
                    </tr>

                </table>
            </td>
        </tr>

        @if(!empty($merchant->trade_license))
            <tr>
                <th>Trade License</th>
                <td>
                    <img id="trade_license" src="{{ asset('uploads/merchant/'.$merchant->trade_license) }}" class="img-fluid img-thumbnail" style="height: 100px;" alt="Merchant Trade License" onmouseover="return fullImage(this.id)" onmouseout="return smallImage(this.id)">
                </td>
            </tr>
        @endif

        @if(!empty($merchant->nid_card))
            <tr>
                <th>NID Card</th>
                <td>
                    <img id="nid_image" src="{{ asset('uploads/merchant/'.$merchant->nid_card) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant NID Card"  onmouseover="return fullImage(this.id)" onmouseout="return smallImage(this.id)">
                </td>
            </tr>
        @endif

        @if(!empty($merchant->tin_certificate))
            <tr>
                <th>TIN Certificate</th>
                <td>
                    <img id="tin_certificate" src="{{ asset('uploads/merchant/'.$merchant->tin_certificate) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant TIN Certificate"  onmouseover="return fullImage(this.id)" onmouseout="return smallImage(this.id)">
                </td>
            </tr>
        @endif

    </table>
  </div>
