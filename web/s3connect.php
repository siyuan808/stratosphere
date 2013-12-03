<?php
if (!class_exists('S3')) require_once 'S3.php';

// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIXSCBXNJH42EW6PQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

$uploadFile = 'S3.php'; // File to save
$bucketName = 'ec2-67-202-55-42-stratos-userid-1'; // Bucket name

// Check if our upload file exists
if (!file_exists($uploadFile) || !is_file($uploadFile)) exit("\nERROR: No such file: $uploadFile\n\n");

// Instantiate the s3 class
$s3 = new S3(awsAccessKey, awsSecretKey);

// Create bucket with public read access
$s3->putBucket($bucketName, S3::ACL_PUBLIC_READ);
	
// Save file with public read access
$s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ);

?>