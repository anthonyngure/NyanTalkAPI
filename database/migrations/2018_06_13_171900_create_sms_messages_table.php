<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateSmsMessagesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('sms_messages', function (Blueprint $table) {
                $table->uuid('id');
                $table->string('number');
                $table->string('status');
                $table->string('status_code');
                $table->string('message_id');
                $table->string('cost');
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
            Schema::dropIfExists('sms_messages');
        }
    }
