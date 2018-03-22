<?php
use Migrations\AbstractSeed;

/**
 * Roles seed.
 */
class RolesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '3',
                'name' => 'admin',
            ],
            [
                'id' => '2',
                'name' => 'ajax',
            ],
            [
                'id' => '4',
                'name' => 'banned',
            ],
            [
                'id' => '1',
                'name' => 'user',
            ],
        ];

        $table = $this->table('roles');
        $table->insert($data)->save();
    }
}
