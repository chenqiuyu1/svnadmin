<?php
/**
 * Created by Jerry.
 * Date: 2017/8/30
 * Time: 19:19
 */
include("include/config.inc.php");

//
// Authentication
//

$engine = \svnadmin\core\Engine::getInstance();

if (!$engine->isProviderActive(PROVIDER_REPOSITORY_EDIT)) {
    $engine->forwardInvalidModule(true);
}

//$engine->checkUserAuthentication(true, ACL_MOD_REPO, ACL_ACTION_ADD);
$appTR->loadModule("branchcreate");

// HTTP request parameters.
$varRepoEnc = get_request_var("r");
$varPathEnc = get_request_var("p");
$varRepo = rawurldecode($varRepoEnc);
$varPath = rawurldecode($varPathEnc);

//
// Actions
//


if (check_request_var('create'))
{
    $engine->handleAction('create_branch');
}

//
// View Data
//

SetValue('OSvnname', ($varRepo && $varPath) ? $varRepo.'/'.$varPath : '');
SetValue('RepositoryParentList', $engine->getRepositoryViewProvider()->getRepositoryParents());
ProcessTemplate("repository/createbranch.html.php");