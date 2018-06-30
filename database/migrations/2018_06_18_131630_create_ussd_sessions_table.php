<?php
    
    use App\UssdSession;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateUssdSessionsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('ussd_sessions', function (Blueprint $table) {
                $table->string('id')->unique();
                $table->string('phone')->index()->nullable();
                $table->string('code')->nullable()->index();
                $table->enum('status', [UssdSession::STATUS_SELECTING_SUB_COUNTY,
                    UssdSession::STATUS_SELECTING_WARD, UssdSession::STATUS_TYPING_NAME,
                    UssdSession::STATUS_TYPING_SUBJECT, UssdSession::STATUS_SELECTING_DEPARTMENT,
                    UssdSession::STATUS_TYPING_TICKET])->nullable();
                $table->string('selected_name')->nullable();
                $table->string('selected_subject')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedInteger('selected_sub_county_id')->nullable();
                $table->foreign('selected_sub_county_id')->references('id')->on('sub_counties')->onDelete('SET NULL');
                $table->unsignedInteger('selected_ward_id')->nullable();
                $table->foreign('selected_ward_id')->references('id')->on('wards')->onDelete('SET NULL');
                $table->unsignedInteger('selected_department_id')->nullable();
                $table->foreign('selected_department_id')->references('id')->on('departments')->onDelete('SET NULL');
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
            Schema::dropIfExists('ussd_sessions');
        }
    }
