<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Questionnaire templates
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Individual questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->integer('order');
            $table->text('question_text');
            $table->string('type')->default('scale'); // scale, boolean, open_text
            $table->json('options')->nullable();      // {"0":"Tidak pernah","1":"Kadang","2":"Sering","3":"Selalu"}
            $table->integer('max_score')->default(3);
            $table->timestamps();
        });

        // Screening sessions
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('questionnaire_id')->constrained()->onDelete('restrict');
            $table->integer('total_score')->nullable();
            $table->string('risk_level')->nullable(); // normal, ringan, sedang, tinggi
            $table->text('open_notes')->nullable();
            $table->string('status')->default('draft'); // draft, completed
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Individual answers
        Schema::create('screening_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('restrict');
            $table->integer('score')->nullable();
            $table->text('answer_text')->nullable();
            $table->timestamps();
        });

        // Risk level thresholds per questionnaire
        Schema::create('risk_thresholds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->string('level'); // normal, ringan, sedang, tinggi, atau custom
            $table->string('label')->nullable(); // nama tampilan, contoh: "Risiko Tinggi"
            $table->integer('score_min');
            $table->integer('score_max');
            $table->string('color_code'); // #22c55e, #eab308, #f97316, #ef4444, dll
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_thresholds');
        Schema::dropIfExists('screening_answers');
        Schema::dropIfExists('screenings');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionnaires');
    }
};