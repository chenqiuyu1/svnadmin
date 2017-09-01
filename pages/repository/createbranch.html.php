<?php GlobalHeader(); ?>

<script type="text/javascript">
    $(document).ready(function(){

        $("#repostructuretype").change(function(){
            var eSingle = $("#repostructuretype-single");
            var eMulti  = $("#repostructuretype-multi");

            eSingle.hide();
            eMulti.hide();

            if ($(this).val() == "single"){ eSingle.show(); }
            else if ($(this).val() == "multi"){ eMulti.show(); }
        });
    });
</script>

<h1><?php Translate("Create branch"); ?></h1>
<p class="hdesc"><?php Translate("Create a new branch to manage your sources."); ?></p>
<div>
    <form method="POST" action="branchcreate.php">
        <div class="form-field">
            <label for="pi"><?php Translate('Repository location'); ?></label>
            <select name="pi" id="pi" class="">
                <?php foreach (GetArrayValue('RepositoryParentList') as $rp) : ?>
                    <option value="<?php print($rp->getEncodedIdentifier()); ?>">
                        <?php print($rp->path); ?>
                        <?php
                        if (!empty($rp->description)) {
                            print(' - ');
                            print($rp->description);
                        }
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="reponame"><?php Translate("trunk name"); ?></label>
            <input type="text" name="reponame" id="reponame" class="lineedit" value="<?php echo GetValue('OSvnname');?>">
            <p>
                <b><?php Translate("Valid signs for trunk name are"); ?>:</b> A-Z, a-z, 0-9, <?php Translate("Underscore"); ?>(_), <?php Translate("Hyphen"); ?>(-) <i><?php Translate("No space!"); ?></i>
            </p>
        </div>
        <div class="form-field">
            <label for="reponame"><?php Translate("branch name"); ?></label>
            <input type="text" name="branchname" id="branchname" class="lineedit">
            <p>
                <b><?php Translate("Valid signs for branch name are"); ?>:</b> A-Z, a-z, 0-9, <?php Translate("Underscore"); ?>(_), <?php Translate("Hyphen"); ?>(-) <i><?php Translate("No space!"); ?></i>
            </p>
        </div>

        <div class="form-field">
            <label for="accesspathcreate"><?php Translate("access path and permission"); ?></label>
            <input type="checkbox" name="accesspathcreate" id="accesspathcreate" value="1" checked> <?php Translate("config it"); ?>
        </div>
        <div class="formsubmit">
            <input type="submit" name="create" value="<?php Translate("Create"); ?>" class="addbtn">
        </div>
    </form>

    <p>
        <a href="repositorylist.php">&#xAB; <?php Translate("Back to overview"); ?></a>
    </p>

</div>

<?php GlobalFooter(); ?>
