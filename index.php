<?php

declare(strict_types=1);

namespace MauticDemo;

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

require_once 'vendor/autoload.php';

class Demo
{
    /** @var MauticApi */
    protected $api;

    /** @var \Mautic\Auth\AuthInterface */
    protected $auth;

    public function __construct()
    {
        $settings = [
            'userName'   => 'admin',
            'password'   => 'mautic',
        ];
        
        $initAuth     = new ApiAuth();
        $this->auth   = $initAuth->newAuth($settings, 'BasicAuth');
        $this->api    = new MauticApi();
        $this->apiUrl = 'http://ddev-mautic3-web/index_dev.php/api/';
    }

    public function run()
    {
        /** @var \Mautic\Api\Users */
        $userApi = $this->api->newApi('users', $this->auth, $this->apiUrl);

        $batch = [
            $this->getUniqueUser(),
            $this->getUniqueUser(),
            $this->getUniqueUser()
        ];
        $batchCount = count($batch);

        $createResponse = $userApi->createBatch($batch);
        $this->handleErrors($createResponse, $batchCount, 'create');

        // Add new IDs
        $ids     = [];
        $counter = 0;
        foreach ($createResponse['users'] as $item) {
            echo "Successfully created user with ID " . $item['id'] . " and username " . $item['username'] . "\n";
            $batch[$counter]['id'] = $item['id'];
            $ids[]                 = $item['id'];

            ++$counter;
        }

        // Edit batch
        $editResponse = $userApi->editBatch($batch, false);
        $this->handleErrors($editResponse, $batchCount, 'edit');

        echo "Successfully updated 3 users\n";

        // Delete batch
        $deleteResponse = $userApi->deleteBatch($ids);
        $this->handleErrors($deleteResponse, $batchCount, 'delete');

        echo "Successfully deleted 3 users\n";
    }

    protected function generateRandomUsername($length = 8)
    {
        $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return 'API_'.substr(str_shuffle(str_repeat($x, intval(ceil($length / strlen($x))))), 1, $length);
    }

    protected function getUniqueUser()
    {
        $username = $this->generateRandomUsername();

        return [
            'username'      => $username,
            'firstName'     => 'API' . $username,
            'lastName'      => 'Test' . $username,
            'email'         => $username.'@email.com',
            'plainPassword' => [
                'password' => 'topSecret007',
                'confirm'  => 'topSecret007',
            ],
            'role' => 1, // Should exist in every Mautic instance
        ];
    }

    protected function handleErrors($response, int $batchCount, string $action)
    {
        if (empty($response['users']) || count($response['users']) !== $batchCount) {
            throw new \Exception('Amount of users in API response not equal to ' . $batchCount);
        }

        if (!empty($response['errors'])) {
            foreach ($response['errors'] as $error) {
                echo "Error in " . $action . " action - " .$error['code'] . ": " . $error['message'] . "\n";
            }
            exit;
        }
    }
}

$demo = new Demo();
$demo->run();
