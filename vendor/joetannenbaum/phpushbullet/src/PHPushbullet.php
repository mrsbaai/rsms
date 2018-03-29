<?php

namespace PHPushbullet;

/**
 * @method array address(string $name, string|array $address)
 * @method array file(string $file_name, string $file_url, string $body = null)
 * @method array link(string $title, string $url, string $body = null)
 * @method array list(string $title, array $items)
 * @method array note(string $title, string $body)
 */

class PHPushbullet
{
    /**
     * An instance of the Guzzle client to make requests
     *
     * @var \GuzzleHttp\Client $api
     */
    protected $api;

    /**
     * The set of devices we are currently pushing to
     *
     * @var array $devices
     */
    protected $devices = [];

    /**
     * The set of users we are currently pushing to,
     * an array of emails
     *
     * @var array $users
     */
    protected $users = [];

    /**
     * The set of channels we are currntly pushing to
     *
     * @var array $channels
     */
    protected $channels = [];

    /**
     * An array of all devices available
     *
     * @var array $all_devices
     */
    protected $all_devices = [];

    public function __construct($access_token = null, Connection $connection =  null, array $config = [])
    {
        $access_token = $access_token ?: getenv('pushbullet.access_token');

        if (!$access_token) {
            throw new \Exception('Your Pushbullet access token is not set.');
        }

        $connection = $connection ?: new Connection($access_token, null, $config);

        $this->api = $connection->client();
    }

    /**
     * Get a list of all of the devices available
     *
     * @return array
     */
    public function devices()
    {
        if (empty($this->all_devices)) {
            $response = $this->fromJson($this->api->get('devices'));

            $this->all_devices = array_map(function ($device) {
                return new Device($device);
            }, $response['devices']);
        }

        return $this->all_devices;
    }

    public function user()
    {
        $this->users = array_merge($this->argsToArray(func_get_args()), $this->users);
        $this->users = array_filter($this->users);
        $this->users = array_unique($this->users);

        return $this;
    }

    /**
     * Set the passed in device(s) for the current push
     *
     * @return \PHPushbullet\PHPushbullet
     */
    public function device()
    {
        $devices = $this->argsToArray(func_get_args());

        foreach ($devices as $destination) {
            $device = $this->getDeviceIden($destination);

            if (!$device) {
                throw new \Exception("{$destination} is not a valid device.");
            }

            $this->devices[] = $device;
        }

        return $this;
    }

    /**
     * Set the passed in channel(s) for the current push
     *
     * @return \PHPushbullet\PHPushbullet
     */
    public function channel()
    {
        $this->channels = array_merge($this->argsToArray(func_get_args()));

        return $this;
    }

    /**
     * Set all of the devices for the current push
     *
     * @return \PHPushbullet\PHPushbullet
     */
    public function all()
    {
        foreach ($this->devices() as $device) {
            if ($device->pushable == true) {
                $this->devices[] = $device->iden;
            }
        }

        return $this;
    }

    /**
     * Actually send the push
     *
     * @return array
     */
    public function push($request)
    {
        if (empty($this->devices) && empty($this->users) && empty($this->channels)) {
            throw new \Exception('You must specify something to push to.');
        }

        $responses = [];

        $destinations = [
            'devices'  => 'device_iden',
            'users'    => 'email',
            'channels' => 'channel_tag',
        ];

        foreach ($destinations as $destination => $key) {
            foreach ($this->{$destination} as $dest) {
                $responses[] = $this->pushRequest($request, [$key => $dest]);
            }
        }

        $this->devices  = [];
        $this->users    = [];
        $this->channels = [];

        return $responses;
    }

    public function getClient()
    {
        return $this->api;
    }

    /**
     * Create push request and... push it
     *
     * @param array $request
     * @param array $merge
     *
     * @return array
     */
    protected function pushRequest($request, $merge)
    {
        $request  = array_merge($request, $merge);
        $response = $this->api->post('pushes', ['json' => $request]);

        return $this->fromJson($response);
    }

    /**
     * Get the `iden` for the device by either the iden or nickname
     *
     * @param string $device
     *
     * @return mixed (boolean|string)
     */
    protected function getDeviceIden($device)
    {
        foreach ($this->devices() as $available_device) {
            foreach (['iden', 'nickname'] as $field) {
                if ($available_device->$field == $device) {
                    return $available_device->iden;
                }
            }
        }

        return false;
    }

    /**
     * @param type $args
     *
     * @return array
     */
    protected function argsToArray($args)
    {
        if (is_array($args[0])) {
            return $args[0];
        }

        return $args;
    }

    protected function fromJson($response)
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Magic method, figures out what sort of push the user is trying to do
     */
    public function __call($method, $arguments)
    {
        $request_class = 'PHPushbullet\Request\Push' . ucwords($method);

        if (!class_exists($request_class)) {
            throw new \Exception(sprintf('Unknown method "%s"', $method));
        }

        $class   = new \ReflectionClass($request_class);
        $request = $class->newInstanceArgs($arguments);

        return $this->push($request->request());
    }
}
