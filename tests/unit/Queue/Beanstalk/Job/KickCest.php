<?php
declare(strict_types=1);

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalconphp.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Test\Unit\Queue\Beanstalk\Job;

use UnitTester;

/**
 * Class KickCest
 */
class KickCest
{
    /**
     * Tests Phalcon\Queue\Beanstalk\Job :: kick()
     *
     * @param UnitTester $I
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2018-11-13
     */
    public function queueBeanstalkJobKick(UnitTester $I)
    {
        $I->wantToTest('Queue\Beanstalk\Job - kick()');
        $I->skipTest('Need implementation');
    }
}
