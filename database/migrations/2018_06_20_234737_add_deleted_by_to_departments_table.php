<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class AddDeletedByToDepartmentsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('departments', function (Blueprint $table) {
                $table->unsignedBigInteger('deleted_by_id')->nullable();
                $table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL');
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('deleted_by_id');
            });
        }
    }
