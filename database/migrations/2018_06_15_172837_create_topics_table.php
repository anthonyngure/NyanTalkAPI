<?php
    
    use App\Topic;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateTopicsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('topics', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->string('description')->nullable();
                $table->enum('status', [Topic::STATUS_PENDING_APPROVAL, Topic::STATUS_APPROVED,
                    Topic::STATUS_REJECTED])->default(Topic::STATUS_PENDING_APPROVAL);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->uuid('forum_id')->nullable();
                $table->foreign('forum_id')->references('id')->on('forums')->onDelete('SET NULL');
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('SET NULL');
                $table->timestamp('rejected_at')->nullable();
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('SET NULL');
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
            Schema::dropIfExists('topics');
        }
    }
