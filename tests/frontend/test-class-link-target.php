<?php

class Yoast_GA_Link_Target_Test extends GA_UnitTestCase {

    /**
     * Make sure yoast_ga_get_domain returns false when we're dealing with a relative url
     *
	 * @covers Yoast_GA_Link_Target::yoast_ga_get_domain
	 */
	public function test_get_domain_RETURNS_false_NO_domain() {
        $category = 'outbound-article';
        $matches  = array(
            0 => '<a href="/out/internal-as-outbound">Link</a>',
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '/out/internal-as-outbound',
            6 => '',
            7 => 'Link',
        );

        $options = array(
            'track_internal_as_outbound' => '/out',
            'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
        );

        $link_target = new Yoast_GA_Link_Target( $category, $matches, $options );

        $this->assertEquals( false, $link_target->yoast_ga_get_domain() );
    }

    /**
     * Make sure yoast_ga_get_domain returns the correct domain and host
     *
     * @covers Yoast_GA_Link_Target::yoast_ga_get_domain
     */
    public function test_get_domain_RETURNS_domain_AND_host_HTTP() {
        $category = 'outbound-article';
        $matches  = array(
            0 => '<a href="http://example.org/out/internal-as-outbound" target="_blank">Link</a>',
            1 => '',
            2 => '"',
            3 => '',
            4 => '',
            5 => 'http://example.org',
            6 => 'target="_blank"',
            7 => 'Link',
        );

        $options = array(
            'track_internal_as_outbound' => '/out',
            'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
        );

        $link_target = new Yoast_GA_Link_Target( $category, $matches, $options );

        $domain        = $link_target->yoast_ga_get_domain();
        $domain_result = is_array( $domain );

        if ( $domain_result ) {
            $this->assertArrayHasKey( 'domain', $domain );
            $this->assertArrayHasKey( 'host', $domain );

            $this->assertEquals( 'example.org', $domain['host'] );
        }
        else {
            $this->assertTrue( $domain_result );
        }
    }

    /**
     * Make sure yoast_ga_get_domain returns the correct domain and host
     *
     * @covers Yoast_GA_Link_Target::yoast_ga_get_domain
     */
     public function test_get_domain_RETURNS_domain_AND_host_HTTPS() {
         $category = 'outbound-article';
         $matches  = array(
             0 => '<a href="http://example.org/out/internal-as-outbound">Link</a>',
             1 => '',
             2 => '',
             3 => '',
             4 => '',
             5 => 'https://example.org',
             6 => '',
             7 => 'Link',
         );

         $options = array(
             'track_internal_as_outbound' => '/out',
             'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
         );
         $link_target = new Yoast_GA_Link_Target( $category, $matches, $options );

         $domain        = $link_target->yoast_ga_get_domain( );
         $domain_result = is_array( $domain );

         if ( $domain_result ) {
             $this->assertArrayHasKey( 'domain', $domain );
             $this->assertArrayHasKey( 'host', $domain );

             $this->assertEquals( 'example.org', $domain['host'] );
         }
         else {
             $this->assertTrue( $domain_result );
         }
     }

}
