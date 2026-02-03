  <div class="row">
      <div class="col-md-10 offset-md-1">
          <table class="table table-sm">
                <tr>
                    <th width="30%">Name</th>
                    <td width="70%">
                        {{ $application->name }}
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        {{ $application->email }}
                    </td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td>
                        {{ $application->contact_number }}
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        {{ $application->address }}
                    </td>
                    </tr>
                    <tr>
                        <th>Photo</th>
                        <td>
                            @if (!empty($application->photo))
                                <img src="{{ asset('uploads/application/' . $application->photo) }}"
                                    class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Play Store Link</th>
                        <td>
                            {{ $application->app_link }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Meta Author</th>
                        <td>
                            {{ $application->meta_author }}
                        </td>
                    </tr>
                    <tr>
                        <th>Meta Keyword</th>
                        <td>
                            {{ $application->meta_keywords }}
                        </td>
                    </tr>
                    <tr>
                        <th>Meta Description</th>
                        <td>
                            {{ $application->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>Google Map</th>
                        <td>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7303.742824617919!2d90.38910370750865!3d23.75196444517022!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2bfbabbf47819a67!2sSTITBD!5e0!3m2!1sen!2sbd!4v1606819209528!5m2!1sen!2sbd" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            @if(!empty($application->google_map))
                                {{-- <iframe src="{!! $application->google_map !!}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe> --}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Google Map</th>
                        <td>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7303.742824617919!2d90.38910370750865!3d23.75196444517022!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2bfbabbf47819a67!2sSTITBD!5e0!3m2!1sen!2sbd!4v1606819209528!5m2!1sen!2sbd" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            @if(!empty($application->google_map))
                                {{-- <iframe src="{!! $application->google_map !!}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe> --}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Action Admin</th>
                        <td>
                            {{ $application->admin->name }}
                        </td>
                    </tr>
          </table>
      </div>
  </div>
