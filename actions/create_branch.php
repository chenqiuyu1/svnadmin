<?php
/**
 * Created by Jerry.
 * Date: 2017/8/30
 * Time: 19:21
 */
if (!defined('ACTION_HANDLING')) {
    die("HaHa!");
}

$engine = \svnadmin\core\Engine::getInstance();

//
// Authentication
//

if (!$engine->isProviderActive(PROVIDER_REPOSITORY_EDIT)) {
    $engine->forwardError(ERROR_INVALID_MODULE);
}

$engine->checkUserAuthentication(true, ACL_MOD_REPO, ACL_ACTION_ADD);

//
// HTTP Request Vars
//

$varParentIdentifierEnc = get_request_var('pi');
$reponame = get_request_var("reponame");
$branchname = get_request_var("branchname");

$varParentIdentifier = rawurldecode($varParentIdentifierEnc);

// Access-path
$reponame_arr = explode('/',$reponame);
$branchname_arr = explode('/',$branchname);
$reponame_access_path = '';
$branchname_access_path = '';
$varPathEnc = '';
$count = 0;
foreach ($reponame_arr as $re){
    if($count == 0){
        $reponame_access_path .= $re.':';

    }else{
        $reponame_access_path .= '/'.$re;
    }
    $count++;
}
$count = 0;
foreach ($branchname_arr as $br){
    if($count == 0){
        $branchname_access_path .= $br.':';
        $varRepoEnc = $br;
    }else{
        $branchname_access_path .= '/'.$br;
        $varPathEnc .=  '/'.$br;
    }
    $count++;
}
$varRepo = rawurldecode($varRepoEnc);
$varPath = rawurldecode(ltrim($varPathEnc,'/'));

//has svnlist
$oR = new \svnadmin\core\entities\Repository($varRepo, $varParentIdentifier);

// Get isExits path.
$isrepoPathList = $engine->getRepositoryViewProvider()->islistPath($oR, $varPath);

if($isrepoPathList){
    $branchname_access_path .= '/' . $re;
}

//
// Validation
//

if ($reponame == NULL) {
    $engine->addException(new ValidationException(tr("You have to fill out all fields.")));
}
else {
    $r = new \svnadmin\core\entities\Repository($reponame, $varParentIdentifier);
    $b = new \svnadmin\core\entities\Repository($branchname, $varParentIdentifier);
    // Create branch.
    try {
        $engine->getRepositoryEditProvider()->copy($r, $b);
        $engine->getRepositoryEditProvider()->save();
        $engine->addMessage(tr("The repository %0 has been created successfully", array($branchname)));

        // Create the access path now.
        try {
            if (get_request_var("accesspathcreate") != NULL
                && $engine->isProviderActive(PROVIDER_ACCESSPATH_EDIT)) {

                $ap = new \svnadmin\core\entities\AccessPath($reponame_access_path);
                $dap = new \svnadmin\core\entities\AccessPath($branchname_access_path);

                if ($engine->getAccessPathEditProvider()->cpAccessPath($ap,$dap)) {
                    $engine->getAccessPathEditProvider()->save();
                }
            }
        }
        catch (Exception $e2) {
            $engine->addException($e2);
        }

    }
    catch (Exception $e) {
        $engine->addException($e);
    }
}
?>