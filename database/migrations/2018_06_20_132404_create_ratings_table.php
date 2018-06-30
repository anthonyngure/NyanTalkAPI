<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateRatingsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('ratings', function (Blueprint $table) {
                $table->uuid('id')->index();
                $table->primary('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedBigInteger('ticket_id')->nullable();
                $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('SET NULL');
                $table->text('text');
                $table->unsignedTinyInteger('stars');
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
            Schema::dropIfExists('ratings');
        }
    }
