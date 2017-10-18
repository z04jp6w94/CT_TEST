<?php 
    //
    //　      _    (_)_(_)
    //　 ___ | |__  _| |_ _ _ _ __ _ _ __
    //　/ __\| __ \| | | | ' ' / _` | '_ \     
    //　| |_ | | | | | | | | |  (_| | | | |	 taipei 
    //　\___/|_| |_|_|_|_|_|_|_\__,_|_| |_|    2013
    //　www.chiliman.com.tw
    // 
    //*****************************************************************************************
    //		撰寫人員：BochiHuang
    //		撰寫日期：20161214
    //		程式功能：Cross Think / 角色權限 / 列表
    //		使用參數：None
    //		資　　料：sel：MUM01
    //		　　　　　ins：None
    //		　　　　　del：None
    //		　　　　　upt：None
    //		修改人員：
    //		修改日期：
    //		註　　解：
    //*****************************************************************************************
    header ('Content-Type: text/html; charset=utf-8');
    session_start();
    //定義全域參數
    //函式庫
    include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
    //路徑及帳號控管
    //$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
    $GP_ID ="000000000000001";
    $GP_NM ="doc_system_definiton_user_jiansheng_themeset1";
    $USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
    $PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
    //資料庫連線
    $MySql = new mysql();
    //使用權限
    //Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限

    //讀取目前的角色結構
	//$TreeStr = ReadTree(0, $GP_NM, $MySql, $GP_ID);

    //echo $defautId;
    //return;
    //$defautId='000000000000004';
    //echo $RoleTreeStatus;
    $RoleTreeStatus = $_SESSION["RoleTreeStatus"];							                                    //權限樹狀態;
    $_SESSION["RoleTreeStatus"] = "";

    $TreeStr = ReadRootTree($defautId,$MySql,$RoleTreeStatus);

    function ReadRootTree($RoleID,$MySql,$RoleTreeStatus){
        $Sql = " Select FDM.Name,FDM.FunctionsId";
        $Sql .= " From FunctionsDetail_M FDM ";
       
        $initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");

        $xstr = "";
        $funStr = ' noDrag="true" noSiblings="true" noDelete="true" noRename="true" ';
        while ($nodeAry = $MySql -> db_fetch_array($initRun)){
            $xstr .= '<li id="node' . $nodeAry[1] . '"' . $funStr . '><a href="#"></a>';

            $Enabled = "N";

            if ($RoleTreeStatus == "View") {
                if ($nodeAry[1] == "000000000000005") $Enabled = GetRoleFunctionsEnabled($RoleID,$nodeAry[1],7,$MySql);
                if($Enabled == "N") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" disabled>' .  $nodeAry[0] . '</input>';   
                if($Enabled == "Y") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" disabled checked>' .  $nodeAry[0] . '</input>';                   
            }else{

                if($RoleTreeStatus == "Add"){
                    if($Enabled == "N") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" onClick="CheckChild(this);">' .  $nodeAry[0] . '</input>';    
                    if($Enabled == "Y") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" checked onClick="CheckChild(this);">' .  $nodeAry[0] . '</input>';                        
                }else{
                    if ($nodeAry[1] == "000000000000005") $Enabled = GetRoleFunctionsEnabled($RoleID,$nodeAry[1],7,$MySql);
                    if($Enabled == "N") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" onClick="CheckChild(this);">' .  $nodeAry[0] . '</input>';    
                    if($Enabled == "Y") $xstr .= '<input type="checkbox" id="FDM' . $nodeAry[1] . '" checked onClick="CheckChild(this);">' .  $nodeAry[0] . '</input>';    
                }

            }
            $xstr .= "<ul>";
            $xstr .= ReadTree($RoleID,$nodeAry[1],$nodeAry[1], $MySql,$RoleTreeStatus);
			$xstr .= "</ul>";
            $xstr .= '</li> ';
        }

        return $xstr;
    }

    function ReadTree($RoleID,$FDMID,$preTreeID, $MySql,$RoleTreeStatus){
		$Sql = " Select FDR.Name,FDR.id ";
        $Sql .= " From FunctionsDetail_R FDR ";
        $Sql .= " Left join FunctionsDetail_M FDM on FDM.ModuleId = FDR.ModuleId ";
		$Sql .= " Where FDM.FunctionsId ='" . $preTreeID . "'";
        $Sql .= " and FDM.FunctionsId = FDR.FunctionsDetailMId";
		
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($SqlRun);
		$xstr = "";
        $funStr = 'noDelete="true" noRename="true" noChildren="true" noAdd="true" ';

        $cbDisable = "";
        //echo $RoleTreeStatus;
        if ($RoleTreeStatus == "View") $cbDisable ="disabled";
		if($RowCount > 0){
			while ($nodeAry = $MySql -> db_fetch_array($SqlRun)){

                $xstr .= '<li id="node' . $preTreeID . '"' . $funStr .'><a href="#"></a>';

                if($RoleTreeStatus == "Add"){
                    $xstr .= '<input type="checkbox" id="FDR' . $nodeAry[1] . '" onClick="CheckParents(this);" >';
                }else{
                    if($RoleTreeStatus == "View"){
                        if (TreeItemEnabe($RoleID,$FDMID,$nodeAry[1],$MySql) == "Y"){
                            $xstr .= '<input type="checkbox" id="FDR' . $nodeAry[1] . '" checked ' . $cbDisable .'>';
                        }else{
                            $xstr .= '<input type="checkbox" id="FDR' . $nodeAry[1] . '" ' . $cbDisable . '>';
                        }      
                    }else{
                        if (TreeItemEnabe($RoleID,$FDMID,$nodeAry[1],$MySql) == "Y"){
                            $xstr .= '<input type="checkbox" id="FDR' . $nodeAry[1] . '" checked onClick="CheckParents(this);" ' . $cbDisable .'>';
                        }else{
                            $xstr .= '<input type="checkbox" id="FDR' . $nodeAry[1] . '" onClick="CheckParents(this);" ' . $cbDisable . '>';
                        }
                    }
              
                }

                $xstr .= $nodeAry[0] ;
                $xstr .= '</input></li> ';
			}
		}
        return $xstr;
	}

    function TreeItemEnabe($RoleID,$FDMID,$FDRID,$MySql){
		$Sql = " Select Type ";
        $Sql .= " From FunctionsDetail_R ";
		$Sql .= " Where FunctionsDetailMId ='$FDMID'";
        $Sql .= " and id ='$FDRID'";

		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
        $pUSER_Func = $MySql -> db_result($SqlRun);
        
        $Enabled =  GetRoleFunctionsEnabled($RoleID,$FDMID,(int)$pUSER_Func,$MySql);
        
        return $Enabled;
    }
   
?>
	<ul id="<?php echo $GP_ID; ?>" class="dhtmlgoodies_tree">
	<?php echo $TreeStr; ?>
	</ul>
	

<script type="text/javascript">
    treeObj = new JSDragDropTree();
    treeObj.setTreeId('<?php echo $GP_ID; ?>');
	treeObj.setRootName('<?php echo $GP_NM; ?>');
    treeObj.setMaximumDepth(1);
    treeObj.setMessageMaximumDepthReached('您僅能設定最多1層目錄(包含根目錄)！'); // If you want to show a message when maximum depth is reached, i.e. on drop.
    treeObj.setAdditionalRenameRequestParameters({ GI: '<?php echo $GP_ID; ?>', ST: 'M' });
	treeObj.setAdditionalDeleteRequestParameters({ GI: '<?php echo $GP_ID; ?>', ST: 'M' });
    treeObj.setAdditionalAddRequestParameters({ GI: '<?php echo $GP_ID; ?>', ST: 'M' });
    treeObj.initTree();
    treeObj.expandAll();

    function CheckChild(obj) {
        var objInput = undefined;
        var objUL = obj.nextElementSibling ;
        
        if(objUL == undefined) return;
        var cntChildren = objUL.children.length;

        for (var i = 0; i < cntChildren; i++) {
            objInput = objUL.children[i].getElementsByTagName("input");
            if (objInput == undefined) continue;
            if (obj.checked == true) {
                $(objInput).prop("checked", true);
            } else {
                $(objInput).prop("checked", false);
            }
        }
    }

    function CheckParents(obj) {
        var objInput = undefined;
        var objLI = obj.parentNode;
        if (objLI == undefined) return;
        objInput = objLI.parentNode.parentNode.getElementsByTagName("input");

        var cnt = 0;
        for (var i = 1; i < objInput.length; i++) {
            if (objInput[i].checked == true) cnt++;
        }

        if (cnt == objInput.length - 1) {
            $(objInput[0]).prop("checked", true);
        } else {
            $(objInput[0]).prop("checked", false);
        }
    }
</script>