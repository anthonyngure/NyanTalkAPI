<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('avatar')->default('images/avatar_placeholder.png');
                $table->enum('type', ['CITIZEN', 'OFFICIAL', 'DEPARTMENT_ADMIN', 'CS', 'ADMIN'])->default('CITIZEN');
                $table->unsignedInteger('department_id')->nullable();
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('SET NULL');
                $table->unsignedInteger('ward_id')->nullable();
                $table->foreign('ward_id')->references('id')->on('wards')->onDelete('SET NULL');
                $table->string('email')->nullable()->unique();
                $table->boolean('email_notifiable')->default(false);
                $table->boolean('sms_notifiable')->default(false);
                $table->boolean('deletable')->default(true);
                $table->string('phone')->nullable()->unique();
                $table->string('phone_verification_code')->nullable();
                $table->timestamp('phone_verification_code_sent_at')->nullable();
                $table->boolean('phone_verified')->default(false);
                $table->string('password')->nullable();
                $table->string('password_recovery_code')->nullable();
                $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
                $table->string('facebook_id')->nullable();
                $table->string('facebook_picture_url')->nullable();
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedBigInteger('deleted_by_id')->nullable();
                $table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->rememberToken();
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
            Schema::dropIfExists('users');
        }
    }
