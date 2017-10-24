





<fieldset>
    <legend>New Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php?action=ierg4210_prod_add" enctype="multipart/form-data">
        <label for="prod_catid">Category *</label>
        <div><select id="prod_catid_" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                ?>
            </select></div>



        <label for="prod_name">Name *</label>
        <div><input id="prod_name_" type="name" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>

        <label for="prod_price">Price *</label>
        <div><input id="prod_price_" type="price" name="price" required="true" pattern="^[\d]+\.\d\d$"
            /></div>

        <label for="prod_name">Discription *</label>
        <div><input id="prod_name_" type="name" name="description" required="true" pattern="^[\w\- ]+$"
            /></div>


        <label for="prod_name">Image *</label>
        <div><input type="file" name="myfile" required="true" accept="image/jpeg,image/gif,image/png" />
        </div>


        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>DELETE Product</legend>
    <form id="prod_delete" method="POST" action="admin-process.php?action=ierg4210_prod_delete" enctype="multipart/form-data">
        <label for="prod_name">product name *</label>
        <div><select id="prod_name_1" name="name">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM products';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>

        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>UPDATE Product</legend>
    <form id="prod_update" method="POST" action="admin-process.php?action=ierg4210_prod_update" enctype="multipart/form-data">
        <label for="prod_catid">Name *</label>
        <div><select id="prod_catid" name="name" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM products';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>

        <label for="prod_catid">New Category *</label>
        <div><select id="prod_catid_" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                ?>
            </select></div>



        <label for="prod_price">New Price *</label>
        <div><input id="prod_price" type="text" name="price" required="true" pattern="^[\d]+\.\d\d$"
            /></div>

        <label for="prod_name">Discription *</label>
        <div><input id="prod_name_" type="name" name="description" required="true" pattern="^[\w\- ]+$"
            /></div>


        <input type="submit" value="Submit" />
    </form>
</fieldset>












<fieldset>
    <legend>New Category</legend>
    <form method="POST" action="admin-process.php?action=ierg4210_cat_add" enctype="multipart/form-data">

        <label for="prod_name">Name *</label>
        <div><input id="prod_name_" type="name" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>

        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>DELETE Cat</legend>
    <form id="prod_delete" method="POST" action="admin-process.php?action=ierg4210_cat_delete" enctype="multipart/form-data">
        <label for="prod_name">product name *</label>
        <div><select id="prod_name_1" name="name">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>

        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>UPDATE Cat</legend>
    <form id="prod_update" method="POST" action="admin-process.php?action=ierg4210_cat_update" enctype="multipart/form-data">
        <label for="prod_catid">Catid *</label>
        <div><select id="prod_catid" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                $conn->close();
                ?>
            </select></div>

        <label for="cat_name">New Name *</label>
        <div><input id="cat_name_" type="text" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>
        <input type="submit" value="Submit" />
    </form>
</fieldset>