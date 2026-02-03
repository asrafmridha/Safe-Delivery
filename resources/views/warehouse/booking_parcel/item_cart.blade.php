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
		
		$items = \Cart::getContent();
		$i = 0;
		foreach($items as $row) {
           echo '<tr>
			<td>'.++$i.'</td>
			<td>'.$row->associatedModel->item_categories->name.'</td>
			<td>'.$row->name.'</td>
			<td>'.$row->associatedModel->units->name.'</td>
			<td>'.$row->price.'</td>
			<td>'.$row->quantity.'</td>
			<td>'.Cart::get($row->id)->getPriceSum().'</td>
			<td><button type="button" class="remove_cart" data-id='.$row->id.' style="color:red;"><i class="fa fa-trash"></i></button></td>
		  </tr>';
		}
	  ?>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Total</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="total_amount" id="total_amount" value="{{ $data->subTotal }}" readonly="">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> VAT(15%)</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="vat_amount" id="vat_amount" value="{{ $data->vatAmount }}" readonly="">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Grand Total</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="grand_amount" id="grand_amount" value="{{ $data->grandTotal }}" readonly="">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Discount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="discount_amount" id="discount_amount" value="" placeholder="Discount" onkeyUp="discount_calculate(this.value)">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Net Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="net_amount" id="net_amount" value="{{ $data->grandTotal }}" placeholder="Net Amount" readonly="">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Paid Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="paid_amount" id="paid_amount" value="" placeholder="Paid Amount" onkeyUp="paid_calculate(this.value)">
		</th>
	  </tr>
	  
	  <tr>
		<th colspan="6" style="text-align:right;"> Due Amount</th>
		<th colspan="2" style="text-align:left;">
			<input type="text" class="form-control" name="due_amount" id="due_amount" value="{{ $data->grandTotal }}" placeholder="Due Amount" readonly="">
		</th>
	  </tr>
	  
    </tbody>
  </table>