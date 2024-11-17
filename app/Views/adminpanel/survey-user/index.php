<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
    <div class="container mt-5">
    <h1><?php echo $title;?></h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($surveys as $majorgrup): ?>
            <tr>
                <td><?= $majorgrup['id'] ?></td>
                <td><?= $majorgrup['name'] ?></td>
                <td><?= $majorgrup['email'] ?></td>
                <td><?= $majorgrup['phone'] ?></td>
                <td><?= $majorgrup['address'] ?></td>
                <td><?= ($majorgrup['user_type']==1)?'Employer':'Institution'?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>