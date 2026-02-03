<table class="table table-bordered">
    <thead>
      <tr>
        <th>SL. No.</th>
        <th>Category Name</th>
        <th>Item Name</th>
        <th>Unit</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php

		$items = \Cart::session($user_id)->getContent();
		$i = 0;
		//print_r($items); exit;
		foreach($items as $row) {
            if($row->associatedModel){
                $category_name = $row->associatedModel->item_categories->name;
                $unit_name = $row->associatedModel->units->name;
            }else{
                $category_name = 'Others';
                $unit_name = $row->attributes->unit_name;
            }

            echo '<tr>
                <td>'.++$i.'</td>
                <td>'.$category_name.'</td>
                <td>'.$row->name.'</td>
                <td>'.$unit_name.'</td>
                <td>'.$row->price.'</td>
                <td>'.$row->quantity.'</td>
                <td>'.Cart::get($row->id)->getPriceSum().'</td>
                <td><button type="button" class="remove_cart" data-id='.$row->id.' style="color:red;"><i class="fa fa-trash"></i></button></td>
            </tr>';
		}
	  ?>

	  <tr>
        <td colspan="3" rowspan="10" style="text-align:left;">
            <table class="table cod_area" style="margin-top:20px;">
                <tr>
                    <th style="width: 30%">Cod Percent </th>
                    <td style="width: 5%"> : </td>
                    <td style="width: 65%">
                        <span id="view_cod_percent">
                            @php
                                /* $cod_percent = ($merchant->cod_charge)? $merchant->cod_charge :0;
                                echo $cod_percent; */
                            @endphp
                            1% (COD Charge 1% for any amount.)
                        </span>
                    <input type="hidden" id="confirm_cod_percent" name="cod_percent" value="2">
                    </td>
                </tr>
                <tr>
                    <th>Collection Amount</th>
                    <td> : </td>
                    <td>
                        <input type="text" name="collection_amount" id="collection_amount" placeholder="Enter Collection Amount" onkeyup="calculate_cod_charge(this.value)" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>Cod Charge</th>
                    <td> : </td>
                    <td>
                        <span id="view_cod_charge">0.00</span>
                        <input type="hidden" id="confirm_cod_charge" name="cod_amount" value="0">
                    </td>
                </tr>
            </table>
        </td>

		<th colspan="3" style="text-align:right;"> Total</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="total_amount" id="total_amount" value="{{ $data->subTotal }}" readonly="">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> VAT(15%)</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="vat_amount" id="vat_amount" value="{{ $data->vatAmount }}" readonly="">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> Grand Total</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="grand_amount" id="grand_amount" value="{{ $data->grandTotal }}" readonly="">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> Discount Percentage (%)</th>
		<th colspan="2" style="text-align:left;">
			<input type="number" step="any" min="0" class="form-control" name="discount_percent" id="discount_percent" value="" placeholder="Discount Percentage" onkeyUp="discount_percent_calculate(this.value)">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> Discount Amount </th>
		<th colspan="2" style="text-align:left;">
			<input type="number" step="any" min="0" class="form-control" name="discount_amount" id="discount_amount" value="" placeholder="Discount Amount" onkeyUp="discount_calculate(this.value)">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> Net Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="net_amount" id="net_amount" value="{{ $data->grandTotal }}" placeholder="Net Amount" readonly="">
		</th>
	  </tr>

      <tr>
          <th colspan="3" style="text-align:right;"> Pickup Charge </th>
          <th colspan="2" style="text-align:left;">
              <input type="text" class="form-control" name="pickup_charge" id="pickup_charge" value="" readonly="" placeholder="Pickup Charge" onkeyup="pickup_charge_calculate(this.value)">
          </th>
      </tr>

      <tr>
          <th colspan="3" style="text-align:right;"> Net Amount with pickup </th>
          <th colspan="2" style="text-align:left;">
              <input type="text" class="form-control" name="total_payable" id="total_payable" value="" placeholder="Net Amount With Pickup" readonly="">
          </th>
      </tr>



	  <tr>
		<th colspan="3" style="text-align:right;"> Paid Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="number" step="any" min="0" class="form-control" name="paid_amount" id="paid_amount" value="" placeholder="Paid Amount" onkeyUp="paid_calculate(this.value)">
		</th>
	  </tr>

	  <tr>
		<th colspan="3" style="text-align:right;"> Due Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="due_amount" id="due_amount" value="{{ $data->grandTotal }}" placeholder="Due Amount" readonly="">
		</th>
	  </tr>

    </tbody>
  </table>
