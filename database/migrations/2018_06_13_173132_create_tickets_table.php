<?php
    
    use App\Ticket;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    
    class CreateTicketsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('tickets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('number')->nullable()->unique()->index();
                $table->unsignedBigInteger('citizen_id')->nullable();
                $table->foreign('citizen_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedInteger('ward_id')->nullable();
                $table->foreign('ward_id')->references('id')->on('wards')->onDelete('SET NULL');
                $table->unsignedInteger('department_id')->nullable();
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('SET NULL');
                $table->timestamp('assigned_official_at')->nullable();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->foreign('assigned_by')->references('id')->on('users')->onDelete('SET NULL');
                $table->unsignedBigInteger('assigned_official_id')->nullable();
                $table->foreign('assigned_official_id')->references('id')->on('users')->onDelete('SET NULL');
                $table->timestamp('share_approved_at')->nullable();
                $table->timestamp('share_requested_at')->nullable();
                $table->unsignedBigInteger('share_approved_by')->nullable();
                $table->foreign('share_approved_by')->references('id')->on('users')->onDelete('SET NULL');
                $table->timestamp('completed_at')->nullable();
                $table->unsignedBigInteger('completed_by')->nullable();
                $table->foreign('completed_by')->references('id')->on('users')->onDelete('SET NULL');
                $table->timestamp('started_at')->nullable();
                $table->unsignedBigInteger('started_by')->nullable();
                $table->foreign('started_by')->references('id')->on('users')->onDelete('SET NULL');
                $table->string('subject');
                $table->text('details');
                $table->text('share_reason')->nullable();
                $table->enum('input_mode', [Ticket::INPUT_MODE_CITIZEN, Ticket::INPUT_MODE_PHONE_CALL,
                    Ticket::INPUT_MODE_WALK_IN])->default(Ticket::INPUT_MODE_CITIZEN);
                $table->enum('status', [Ticket::STATUS_PENDING_ASSIGNMENT,
                    Ticket::STATUS_ASSIGNED, Ticket::STATUS_ASSIGNED_DEPARTMENT, Ticket::STATUS_STARTED,
                    Ticket::STATUS_PENDING_SHARE_APPROVAL, Ticket::STATUS_COMPLETED])
                    ->default(Ticket::STATUS_PENDING_ASSIGNMENT);
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
            Schema::dropIfExists('tickets');
        }
    }
