<?php
	session_start();

	$search = $_GET['search'];
	require_once('db/db_config.php');
				
	$sql=mysqli_query($connection,"select * from member_info where LOWER(name) like '%$search%' or contact like '%$search%'");
	echo "----";
	print('<tr><th>NAME</th><th>CONTACT</th><th>FORM_NO</th>
			<th>BASIC</th><th>TRAINING</th><th>DIET</th><th>DETAILS</th></tr>');
			while($row=mysqli_fetch_array($sql))
			{	
				$member_id=$row['member_id'];
				$name=$row['name'];
				$contact=$row['contact'];
				$form_no=$row['form_no'];
				$image=$member_id."jpg";
				$color="#F9D1CA";

				

				print "<tr><td>";
				print "<input  type='button' id='modal_display' data-toggle='modal'
				class='btn btn-link btn-xs'	data-target='#modal_details' name='$member_id' value='$name'>";
					//echo $name; 
				print "</td> <td>";
				echo $contact; 
				print "</td> <td>";
				echo $form_no; 




				$sql1=mysqli_query($connection,"SELECT p.p_amount-b.paid,DATEDIFF(b.end_date,current_date) FROM packages p join basic_package b on p.package_id=b.package_id WHERE b.member_id=$member_id and current_date between b.start_date and b.end_date");
		if (mysqli_num_rows($sql1))
		{ 
			while ($row1=mysqli_fetch_array($sql1)) {
				$b_amt_left=$row1[0];
				$b_left=$row1[1];

				if ($_SESSION['access_level1']>2) {
					if ($b_amt_left>0) {
						print "</td> <td bgcolor='$color'>";
						print "$b_left";
					} else{
						print "</td> <td>";
						print "$b_left";
					}
				} else {


					if ($b_amt_left>0) {
						print "</td> <td bgcolor='$color'>";
						print "<button class='btn btn-danger btn-xs' type='button' onclick=\"window.open('b_edit.php?member_id=$member_id','popUpWindow$member_id','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$b_left</button>";
					} else{
						print "</td> <td>";
						print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('b_edit.php?member_id=$member_id','popUpWindow$member_id','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$b_left</button>";
					}
				}
			}
		}
		else
			{	$b_left=0;
				print "</td> <td>";

				if ($_SESSION['access_level1']>2) {
					print "$b_left";
				} else {
					


					print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('b_edit.php?member_id=$member_id','popUpWindow$member_id','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$b_left</button>";
				}
			}


			$sql2=mysqli_query($connection,"
				SELECT p.p_amount-t.paid,DATEDIFF(t.end_date,current_date) FROM packages p join training_package t on p.package_id=t.package_id WHERE t.member_id=$member_id and current_date between t.start_date and t.end_date");
			if (mysqli_num_rows($sql2))
			{ 
				while ($row2=mysqli_fetch_array($sql2)) {
					$t_amt_left=$row2[0];
					$t_left=$row2[1];
					if ($_SESSION['access_level1']>2) {
						if ($t_amt_left>0) {
							print "</td> <td bgcolor='$color'>";
							print "$t_left";
						} else {
							print "</td> <td>";
							print "$t_left";
						}
					} else {
						if ($t_amt_left>0) {
							print "</td> <td bgcolor='$color'>";
							print "<button class='btn btn-danger btn-xs' type='button' onclick=\"window.open('t_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$t_left</button>";
						} else {
							print "</td> <td>";
							print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('t_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$t_left</button>";
						}
					}
				}
			}
			else{
				$t_left=0;
				print "</td> <td>";
				if ($_SESSION['access_level1']>2) {
					print "$t_left";
				} else {
					


					print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('t_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$t_left</button>";
				}
			}


			$sql3=mysqli_query($connection,"SELECT p.p_amount-d.paid,DATEDIFF(d.end_date,current_date) FROM packages p join diet_package d on p.package_id=d.package_id WHERE d.member_id=$member_id and current_date between d.start_date and d.end_date");
			if (mysqli_num_rows($sql3))
			{ 
				while ($row3=mysqli_fetch_array($sql3)) {
					$d_amt_left=$row3[0];
					$d_left=$row3[1];
					if ($_SESSION['access_level1']>2) {
						if ($d_amt_left) {
							print "</td> <td bgcolor='$color'>";
							print "$d_left";
						} else {
							print "</td> <td>";
							print "$d_left";
						}
						
					} else {
						
						
						
						if ($d_amt_left) {
							print "</td> <td bgcolor='$color'>";
							print "<button class='btn btn-danger btn-xs' type='button' onclick=\"window.open('d_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$d_left</button>";
						} else {
							print "</td> <td>";
							print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('d_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$d_left</button>";
						}
					}
				}
			}
			else{
				$t_left=0;
				print "</td> <td>";
				if ($_SESSION['access_level1']>2) {
					print "$t_left";	
				} else {
					print "<button class='btn btn-primary btn-xs' type='button' onclick=\"window.open('d_edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">$t_left</button>";	
				}
			}

					print "</td> <td>";
					print " <button id=$member_id type='button' class='btn btn-info btn-xs' name='EDIT'  onclick=\"window.open('edit.php?member_id=$member_id','popUpWindow','resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">EDIT</button>";

					print "</td> </tr>";
				
}
	?>