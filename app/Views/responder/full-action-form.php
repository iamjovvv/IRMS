<h1>Incident <?= htmlspecialchars($incident['tracking_code']) ?></h1>

<p><strong>Description:</strong> <?= htmlspecialchars($incident['description']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($incident['status']) ?></p>

<h2>Media</h2>
<?php foreach ($media as $m): ?>
    <img src="/uploads/<?= htmlspecialchars($m['file_name']) ?>" alt="Media" />
<?php endforeach; ?>

<h2>Assessments</h2>
<?php foreach ($assessments as $a): ?>
    <p><?= htmlspecialchars($a['note']) ?> (<?= $a['created_at'] ?>)</p>
<?php endforeach; ?>

<h2>Actions</h2>
<?php foreach ($actions as $act): ?>
    <p><?= htmlspecialchars($act['action_taken']) ?> by <?= htmlspecialchars($act['responder']) ?></p>
<?php endforeach; ?>

<h2>Update Status</h2>
<form method="POST" action="/RMS/public/index.php?url=responder/submitAction">
    <input type="hidden" name="incident_id" value="<?= $incident['id'] ?>">
    <textarea name="action_taken" placeholder="Describe your action..." required></textarea>
    <button type="submit">Submit Action</button>
</form>