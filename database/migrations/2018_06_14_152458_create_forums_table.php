<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateForumsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('forums', function (Blueprint $table) {
                $table->uuid('id')->index();
                $table->primary('id');
                $table->string('name');
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedBigInteger('last_edited_by_id')->nullable();
                $table->foreign('last_edited_by_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedBigInteger('deleted_by_id')->nullable();
                $table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('forums');
        }
    }
