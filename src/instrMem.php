<?php
require "functions.php";
?>
<br>


<?php
$mem=$_SESSION['MemIstr'];
$dim=$_SESSION['MemIstrDim'];
$dim=intval($dim);

if ($dim!=0)
{	
    $index=0;
    while($index<$dim)
    {
        $a=$mem[$index];
        $op=substr($a,25,7);
        $funct3=substr($a,17,3);
        $funct7=substr($a,0,7);

        //var_dump($op,$a);exit;
        $tipo=instrType(BinToInt($op,1));
        $oper=instrName(BinToInt($op,1),BinToInt($funct3,1),BinToInt($funct7,1));
        $istruzione='';

        if($tipo=="R")
        {
            $rd=substr($a,20,5);
            $rs1=substr($a,12,5);
            $rs2=substr($a,7,5);
            $istruzione=$oper." ".codRegister(BinToInt($rd,1)).", ".codRegister(BinToInt($rs1,1)).", ".codRegister(BinToInt($rs2,1));
        }
        else if($tipo=="I")
        {
            $rd=substr($a,20,5);
            $rs1=substr($a,12,5);
            $imm=substr($a,0,12);
			$check=BinToInt($op,1);
            if($check==hexdec(3) || $check==hexdec(67))
            {
                $istruzione=$oper." ".codRegister(BinToInt($rd,1)).", ".BinToInt($imm,0)."(".codRegister(BinToInt($rs1,1)).")";
            }
            else
            {
                $istruzione=$oper." ".codRegister(BinToInt($rd,1)).", ".codRegister(BinToInt($rs1,1)).", ".BinToInt($imm,0);
            }

        }
        else if($tipo=="S")
        {
            $imm=substr($a,0,7).substr($a,20,5);
            $rs1=substr($a,12,5);
            $rs2=substr($a,7,5);
            $istruzione=$oper." ".codRegister(BinToInt($rs2,1)).", ".BinToInt($imm,0)."(".codRegister(BinToInt($rs1,1)).")";
        }
        else if($tipo=="SB")
        {
            $imm=substr($a,0,1).substr($a,24,1).substr($a,1,6).substr($a,20,4).'0';
            $rs1=substr($a,12,5);
            $rs2=substr($a,7,5);
            $istruzione=$oper." ".codRegister(BinToInt($rs1,1)).", ".codRegister(BinToInt($rs2,1)).", ".BinToInt($imm,0)*2;
        }
        else if($tipo=="U")
        {
            $rd=substr($a,20,5);
            $imm=substr($a,0,20);
            $istruzione=$oper." ".codRegister(BinToInt($rd,1)).", ".BinToInt($imm,0);
        }
        else if($tipo=="UJ")
        {
            $rd=substr($a,20,5);
            $imm=substr($a,0,1).substr($a,12,8).substr($a,11,1).substr($a,1,10).'0';
            $istruzione=$oper." ".codRegister(BinToInt($rd,1)).", ".BinToInt($imm,0)*2;
        }


        ?>
        <br>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" ID="Table1">
            
		<?php
			$text1='<tr><td align="center" valign="middle" bgcolor=';
			$text2='> <font size="2" face="arial" color="black">';
			$text3='</font> </td></tr>';
			if ($_SESSION['ifIstruzione']==$index) {
				$color='pink';
				$message='INSTRUCTION IN IF STAGE';
				$message=($_SESSION['idIstruzione']==1001)?$message.'<b style="position: absolute; font-size: 20px; margin-left: 2px;">*</b>':$message;
				echo $text1.$color.$text2.$message.$text3;

			}
			if ($_SESSION['idIstruzione']==$index) {
				$color='red';
				$message='INSTRUCTION IN ID STAGE';
				$message=($_SESSION['exIstruzione']==1001)?$message.'<b style="position: absolute; font-size: 20px; margin-left: 2px;">*</b>':$message;
				echo $text1.$color.$text2.$message.$text3;
			}
			if ($_SESSION['exIstruzione']==$index) {
				$color='yellow';
				$message='INSTRUCTION IN EX STAGE';
				$message=($_SESSION['memIstruzione']==1001)?$message.'<b style="position: absolute; font-size: 20px; margin-left: 2px;">*</b>':$message;
				echo $text1.$color.$text2.$message.$text3;
			}
			if ($_SESSION['memIstruzione']==$index) {
				$color='blue';
				$message='INSTRUCTION IN MEM STAGE';
				$message=($_SESSION['wbIstruzione']==1001)?$message.'<b style="position: absolute; font-size: 20px; margin-left: 2px;">*</b>':$message;
				echo $text1.$color.$text2.$message.$text3;
			}
			if ($_SESSION['wbIstruzione']==$index) {
				$color='green';
				$message='INSTRUCTION IN WB STAGE';
				echo $text1.$color.$text2.$message.$text3;
			}
			
		?>
            <tr>
                <td width="40%" align="center" valign="middle" bgcolor="white">
                    <font size="1" face="arial">
                        <b>Address <?php echo $index*4;?> (0x<?php echo dechex($index*4);?>)</b><br>
                        <?php     echo $tipo;?>-type Instruction:<br>
                    </font>
                    <font size="3" face="arial">
                        <b><?php     echo $istruzione;?></b>
                    </font>
                    <br>
                    <font size="2" face="arial">
                        <b><?php     echo $a;?></b>
                    </font>
                </td>
            </tr>
            <tr>
                <td width="60%" bgcolor="#cccccc" valign="top" align="center">
                    <?php     if ($tipo=="R")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table2">
                            <tr>
								<td width="20%" align="center"><font size="1"><?php       echo BinToInt($funct7,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs2,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs1,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($funct3,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rd,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
                                <td width="20%" align="center"><font size="1"><?php       echo $funct7;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rs2;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rs1;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $funct3;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rd;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
                                <td width="20%" align="center"><font size="1">FUNCT7</font></td>
                                <td width="15%" align="center"><font size="1">RS2</font></td>
                                <td width="15%" align="center"><font size="1">RS1</font></td>
                                <td width="15%" align="center"><font size="1">FUNCT3</font></td>
                                <td width="15%" align="center"><font size="1">RD</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                    <?php     if ($tipo=="I")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table3">
                            <tr>
								<td width="35%" align="center"><font size="1"><?php       echo BinToInt($imm,0);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs1,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($funct3,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rd,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1"><?php       echo $imm;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rs1;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $funct3;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rd;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1">IMMEDIATE</font></td>
                                <td width="15%" align="center"><font size="1">RS1</font></td>
                                <td width="15%" align="center"><font size="1">FUNCT3</font></td>
                                <td width="15%" align="center"><font size="1">RD</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                    <?php     if ($tipo=="S")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table3">
						    <tr>
								<td width="35%" align="center"><font size="1"><?php       echo BinToInt($imm,0);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs2,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs1,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($funct3,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1"><?php       echo $imm;?></font></td>
								<td width="15%" align="center"><font size="1"><?php       echo $rs2;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rs1;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $funct3;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1">IMMEDIATE</font></td>
	                            <td width="15%" align="center"><font size="1">RS2</font></td>
                                <td width="15%" align="center"><font size="1">RS1</font></td>
                                <td width="15%" align="center"><font size="1">FUNCT3</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                    <?php     if ($tipo=="SB")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table3">
							<tr>
								<td width="35%" align="center"><font size="1"><?php       echo BinToInt($imm,0);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs2,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($rs1,1);?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo BinToInt($funct3,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1"><?php       echo $imm;?></font></td>
								<td width="15%" align="center"><font size="1"><?php       echo $rs2;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $rs1;?></font></td>
                                <td width="15%" align="center"><font size="1"><?php       echo $funct3;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
								<td width="35%" align="center"><font size="1">IMMEDIATE</font></td>
	                            <td width="15%" align="center"><font size="1">RS2</font></td>
                                <td width="15%" align="center"><font size="1">RS1</font></td>
                                <td width="15%" align="center"><font size="1">FUNCT3</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                    <?php     if ($tipo=="U")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table4">
                            <tr>
                                <td width="60%" align="center"><font size="1"><?php       echo BinToInt($imm,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($rd,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
                                <td width="60%" align="center"><font size="1"><?php       echo $imm;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $rd;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
                                <td width="60%" align="center"><font size="1">ADDRESS</font></td>
                                <td width="20%" align="center"><font size="1">RD</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                    <?php     if ($tipo=="UJ")
                    {
                        ?>
                        <table width="280" cellpadding="2" cellspacing="0" style="border:1px solid #666666" ID="Table4">
                            <tr>
                                <td width="60%" align="center"><font size="1"><?php       echo BinToInt($imm,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($rd,1);?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo BinToInt($op,1);?></font></td>
                            </tr>
                            <tr>
                                <td width="60%" align="center"><font size="1"><?php       echo $imm;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $rd;?></font></td>
                                <td width="20%" align="center"><font size="1"><?php       echo $op;?></font></td>
                            </tr>
                            <tr>
                                <td width="60%" align="center"><font size="1">ADDRESS</font></td>
                                <td width="20%" align="center"><font size="1">RD</font></td>
                                <td width="20%" align="center"><font size="1">OP</font></td>
                            </tr>
                        </table>
                    <?php     } ?>
                </td>
            </tr>
        </table>
        <?php
        $index=$index+1;
    }

}
else
{

    ?>

    <br>
    <div align="center" class="testoGrande">
        Instruction Memory is EMPTY
        <form action="editor.php" method="post" target="Body" ID="Form1">
            <input type="submit" value="Click HERE to load a program" name="load" class="form" ID="Submit1">
        </form>
    </div>
<?php } ?>
