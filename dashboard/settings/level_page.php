<!DOCTYPE html>
<html>
<head>
	<title>Level System with KYC Verification and Bank Details</title>
</head>
<body>
	<h1>Level System with KYC Verification and Bank Details</h1>
	<p>Welcome, <?php echo $name; ?>!</p>
	<p>Your current level is <?php echo $level; ?></p>
	<p>Your total points: <?php echo $points; ?></p>

	<?php if ($level == 'Tier1' && !$kyc_verified): ?>
		<p>Please complete KYC verification to upgrade to Tier2.</p>
		<a href="kyc_verification.php">KYC Verification</a>
	<?php endif; ?>

	<?php if ($level == 'Tier2' && !$bank_details_added): ?>
		<p>Please add your bank details to upgrade to Tier3.</p>
		<a href="bank_details.php">Bank Details</a>
	<?php endif; ?>
</body>
</html>
