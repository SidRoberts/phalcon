<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Support\Settings;

use Phalcon\Support\Settings;
use Phalcon\Tests\AbstractUnitTestCase;

final class SettingsGetSetTest extends AbstractUnitTestCase
{
    /**
     * Restore all overrides after each test so state does not leak.
     */
    protected function tearDown(): void
    {
        Settings::reset();

        parent::tearDown();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportSettingsDefaults(): void
    {
        $this->assertSame(
            true,
            Settings::get('db.escape_identifiers')
        );

        $this->assertSame(
            false,
            Settings::get('db.force_casting')
        );

        $this->assertSame(
            false,
            Settings::get('form.strict_entity_property_check')
        );

        $this->assertSame(
            false,
            Settings::get('orm.case_insensitive_column_map')
        );

        $this->assertSame(
            false,
            Settings::get('orm.cast_last_insert_id_to_int')
        );

        $this->assertSame(
            false,
            Settings::get('orm.cast_on_hydrate')
        );

        $this->assertSame(
            true,
            Settings::get('orm.column_renaming')
        );

        $this->assertSame(
            false,
            Settings::get('orm.disable_assign_setters')
        );

        $this->assertSame(
            true,
            Settings::get('orm.enable_implicit_joins')
        );

        $this->assertSame(
            true,
            Settings::get('orm.enable_literals')
        );

        $this->assertSame(
            true,
            Settings::get('orm.events')
        );

        $this->assertSame(
            false,
            Settings::get('orm.exception_on_failed_save')
        );

        $this->assertSame(
            true,
            Settings::get('orm.exception_on_failed_metadata_save')
        );

        $this->assertSame(
            false,
            Settings::get('orm.ignore_unknown_columns')
        );

        $this->assertSame(
            false,
            Settings::get('orm.late_state_binding')
        );

        $this->assertSame(
            true,
            Settings::get('orm.not_null_validations')
        );

        $this->assertSame(
            0,
            Settings::get('orm.resultset_prefetch_records')
        );

        $this->assertSame(
            true,
            Settings::get('orm.update_snapshot_on_save')
        );

        $this->assertSame(
            true,
            Settings::get('orm.virtual_foreign_keys')
        );

        $this->assertSame(
            true,
            Settings::get('orm.dynamic_update')
        );

        $this->assertNull(
            Settings::get('unknown')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportSettingsGetSet(): void
    {
        $this->assertSame(
            true,
            Settings::get('db.escape_identifiers')
        );

        $this->assertSame(
            false,
            Settings::get('db.force_casting')
        );

        Settings::set('db.escape_identifiers', false);

        $this->assertSame(
            false,
            Settings::get('db.escape_identifiers')
        );

        Settings::set('db.force_casting', true);

        $this->assertSame(
            true,
            Settings::get('db.force_casting')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-11
     */
    public function testSupportSettingsSetAllKnownKeys(): void
    {
        $keys = [
            'form.strict_entity_property_check',
            'orm.case_insensitive_column_map',
            'orm.cast_last_insert_id_to_int',
            'orm.cast_on_hydrate',
            'orm.column_renaming',
            'orm.disable_assign_setters',
            'orm.enable_implicit_joins',
            'orm.enable_literals',
            'orm.exception_on_failed_save',
            'orm.exception_on_failed_metadata_save',
            'orm.ignore_unknown_columns',
            'orm.late_state_binding',
            'orm.not_null_validations',
            'orm.resultset_prefetch_records',
            'orm.update_snapshot_on_save',
            'orm.virtual_foreign_keys',
            'orm.dynamic_update',
        ];

        foreach ($keys as $key) {
            $original = Settings::get($key);

            Settings::set($key, true);

            $this->assertTrue(
                Settings::get($key)
            );

            Settings::reset();

            $this->assertSame($original, Settings::get($key));
        }
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-11
     */
    public function testSupportSettingsSetUnknownKeyIgnored(): void
    {
        Settings::set('unknown.key', true);

        $this->assertNull(
            Settings::get('unknown.key')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-04
     */
    public function testSupportSettingsReset(): void
    {
        Settings::set('db.escape_identifiers', false);
        Settings::set('db.force_casting', true);
        Settings::set('orm.events', false);

        $this->assertSame(false, Settings::get('db.escape_identifiers'));
        $this->assertSame(true, Settings::get('db.force_casting'));
        $this->assertSame(false, Settings::get('orm.events'));

        Settings::reset();

        $this->assertSame(true, Settings::get('db.escape_identifiers'));
        $this->assertSame(false, Settings::get('db.force_casting'));
        $this->assertSame(true, Settings::get('orm.events'));
    }
}
