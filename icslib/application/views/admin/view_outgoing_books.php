<div id="thisbody" class="body width-fill background-white">
					<div class="cell">
						<div class="page-header cell">
                                        <h1>Admin <small>Outgoing Books</small></h1>
                                    </div>
                        <?php
                            if($query != NULL){
                        ?>
						<div class="panel datasheet cell">
	                        <div class="header background-red">
	                            List of outgoing books
	                        </div>
	                        <table class="body">
	                            <thead>
	                                <tr>
	                                    <th style="width: 2%;">#</th>
	                                    <th style="width: 10%;">Borrower's Acct No</th>
	                                    <th style="width: 10%;">Borrower's Name</th>
	                                    <th style="width: 10%;">Book Call Number</th>
										<th style="width: 10%;">Status</th>
										<th style="width: 10%;">Due Date</th>
	                                    <th style="width: 10%;"></th>
	                                    <th style="width: 10%;"></th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            	<?php
	                            	$count = 1;
	                                foreach($query as $row) {
										echo "<tr>
											<td>$count</td>
											<td>{$row->account_number}</td>
											<td>{$row->first_name} {$row->middle_initial} {$row->last_name}</td>
											<td>{$row->call_number}</td>
											<td>{$row->status}</td>
											<td>{$row->due_date}</td>";
										echo "<td><form action='controller_outgoing_books/reserve/' method='post'>
											<input type='hidden' name='res_number' value='{$row->res_number}' />
											<input type='submit' class='background-red' name='reserve' value='Confirm' />
										</form></td>";				//button to be clicked if the reservation will be approved; functionality of this not included
										echo "<td><form action='controller_outgoing_books/cancel/' method='post'>
											<input type='hidden' name='res_number' value='{$row->res_number}' />
											<input type='submit' class='background-red' name='cancel' value='Cancel' />
										</form></td>";				//button to be clicked if the reservation will be cancelled; functionality of this not included
										echo "</tr>";

										$count++;
									}
									?>
	                            </tbody>
	                        </table>
	                        <div class="footer pagination">
	                            <ul class="nav">
	                                <li><a href="#">Prev</a></li>
	                                <li><a href="#">Next</a></li>
	                            </ul>
	                        </div>
	                    </div>
	                    <?php
	                    	}
	                    	else{
	                    		echo "<hr>";
                                echo "<h2 class='color-grey'>There is no currently outgoing books!</h2>";
                                echo "<hr>";
	                    	}
	                    ?>
	                </div>				
	            </div>