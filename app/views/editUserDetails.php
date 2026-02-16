<?php require 'components/head.php'?>
    <form action="<?php echo URLROOT; ?>/EmployeeList/updateUser/<?php echo $data['user']->User_ID; ?>" method="POST">

        <input type="hidden" name="User_id" value="<?php echo $data['user']->User_ID; ?>">

        <div>
            <label for="username">Username</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?php echo htmlspecialchars($data['user']->username); ?>"
                   required>
        </div>

        <div>
            <label for="email">Email Address</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?php echo htmlspecialchars($data['user']->email); ?>"
                   required>
        </div>

        <div>
            <label for="role">User Role</label>
            <select id="role" name="role">
                <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="manager" <?php echo ($data['user']->role == 'manager') ? 'selected' : ''; ?>>Manager</option>
                <option value="employee" <?php echo ($data['user']->role == 'employee') ? 'selected' : ''; ?>>Employee</option>
            </select>
        </div>

        <div>
            <input type="checkbox"
                   id="is_active"
                   name="is_active"
                   value="1"
                <?php echo ($data['user']->is_active == 1) ? 'checked' : ''; ?>>
            <label for="is_active">Account Active</label>
        </div>

        <div>
            <button type="submit">Save Changes</button>
            <a href="<?php echo URLROOT; ?>/EmployeeList">Cancel</a>
        </div>
        <a href="<?php echo URLROOT; ?>/EmployeeList/deleteUser/<?php echo $data['user']->User_ID; ?>"
           style="color: red; margin-left: 20px;"
           onclick="return confirm('WARNING: Are you sure you want to PERMANENTLY delete <?php echo $data['user']->username; ?>? This action cannot be undone.');">
            Delete User
        </a>
    </form>

<?php require 'components/footer.php'?>