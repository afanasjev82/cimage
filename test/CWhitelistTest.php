<?php

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A testclass
 *
 */
class CWhitelistTest extends \PHPUnit\Framework\TestCase
{
    /*
     * remote_whitelist
     */
    private $remote_whitelist = [
        '\.facebook\.com$',
        '^(?:images|photos-[a-z])\.ak\.instagram\.com$',
        '\.google\.com$',
    ];



    /**
     * Provider for hostname matching the whitelist.
     *
     * @return array
     */
    public static function providerHostnameMatch()
    {
        return [
            ["any.facebook.com"],
            ["images.ak.instagram.com"],
            ["www.google.com"],
        ];
    }



    /**
     * Provider for hostname not matching the whitelist.
     *
     * @return array
     */
    public static function providerHostnameNoMatch()
    {
        return [
            ["example.com"],
            [".com"],
            ["img.jpg"],
        ];
    }



    /**
     * Test
     *
     * @param string $hostname matches the whitelist
     *
     * @return void
     */
    #[DataProvider('providerHostnameMatch')]
    public function testRemoteHostWhitelistMatch($hostname)
    {
        $whitelist = new CWhitelist();
        $whitelist->set($this->remote_whitelist);

        $res = $whitelist->check($hostname);
        $this->assertTrue($res, "Should be a valid hostname on the whitelist: '$hostname'.");
    }



    /**
     * Test
     *
     * @param string $hostname not matching the whitelist
     *
     * @return void
     */
    #[DataProvider('providerHostnameNoMatch')]
    public function testRemoteHostWhitelistNoMatch($hostname)
    {
        $whitelist = new CWhitelist();
        $whitelist->set($this->remote_whitelist);

        $res = $whitelist->check($hostname);
        $this->assertFalse($res, "Should not be a valid hostname on the whitelist: '$hostname'.");
    }



    /**
     * Test
     *
     * @return void
     *
     */
    public function testInvalidFormat()
    {
        $this->expectException(Exception::class);
        $whitelist = new CWhitelist();
        $whitelist->set("should fail");
    }



    /**
     * Test
     *
     * @return void
     *
     */
    public function testCheckEmpty()
    {
        $whitelist = new CWhitelist();
        $whitelist->set($this->remote_whitelist);

        $hostname = "";
        $res = $whitelist->check($hostname);
        $this->assertFalse($res, "Should not be a valid hostname on the whitelist: '$hostname'.");
    }
}
