<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tentor;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoCourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Buat tentor
            $tentor = User::updateOrCreate(
                ['email' => 'tentor2@eduria.test'],
                [
                    'name' => 'Budi Santoso',
                    'password' => Hash::make('password'),
                    'role' => 'tentor',
                ]
            );

            if (!$tentor->tentor) {
                Tentor::create([
                    'user_id' => $tentor->id,
                    'unique_id' => $this->generateTentorId(),
                ]);
            }

            // 2. Buat course
            $course = Course::updateOrCreate(
                ['title' => 'English Fundamentals'],
                [
                    'description' => 'Pelajari dasar-dasar bahasa Inggris: grammar, reading, dan writing untuk pemula.',
                    'tentor_id' => $tentor->id,
                ]
            );

            // 3. Buat 3 modules
            $modules = [
                [
                    'title' => 'Parts of Speech & Basic Tenses',
                    'content' => "<h2>Parts of Speech</h2><p>There are eight parts of speech in English: nouns, pronouns, verbs, adjectives, adverbs, prepositions, conjunctions, and interjections.</p><h3>1. Nouns</h3><p>Nouns name people, places, things, or ideas. Examples: <em>book, teacher, Jakarta, freedom</em>.</p><h3>2. Verbs</h3><p>Verbs describe actions or states of being. Examples: <em>run, eat, is, are</em>.</p><h3>3. Adjectives</h3><p>Adjectives describe nouns. Examples: <em>beautiful, tall, interesting</em>.</p><h3>Basic Tenses</h3><p><strong>Present Tense:</strong> I <em>study</em> English every day.</p><p><strong>Past Tense:</strong> I <em>studied</em> English yesterday.</p><p><strong>Future Tense:</strong> I <em>will study</em> English tomorrow.</p>",
                    'video_url' => 'https://www.youtube.com/watch?v=BDASLxE8fCY',
                ],
                [
                    'title' => 'Reading Comprehension Strategies',
                    'content' => "<h2>How to Improve Reading Comprehension</h2><p>Reading comprehension is the ability to understand and interpret what you read. Here are some strategies:</p><h3>1. Skimming</h3><p>Read the title, headings, and first sentences of each paragraph to get the main idea quickly.</p><h3>2. Scanning</h3><p>Look for specific information like names, dates, or keywords without reading every word.</p><h3>3. Context Clues</h3><p>When you find an unfamiliar word, look at the surrounding words and sentences to guess its meaning.</p><h3>4. Summarizing</h3><p>After reading a paragraph, try to summarize it in one sentence in your own words.</p><h3>Practice Tip</h3><p>Read a short article every day and write down 3 new vocabulary words with their meanings.</p>",
                ],
                [
                    'title' => 'Introduction to Writing',
                    'content' => "<h2>Basic Writing Skills</h2><p>Good writing is clear, organized, and engaging. Here are the key elements:</p><h3>Paragraph Structure</h3><p>A paragraph has three parts: <strong>topic sentence</strong> (main idea), <strong>supporting sentences</strong> (details), and <strong>concluding sentence</strong> (summary).</p><h3>The Writing Process</h3><ol><li><strong>Prewriting:</strong> Brainstorm ideas and organize them.</li><li><strong>Drafting:</strong> Write your first version without worrying about mistakes.</li><li><strong>Revising:</strong> Improve content, structure, and word choice.</li><li><strong>Editing:</strong> Fix grammar, spelling, and punctuation.</li><li><strong>Publishing:</strong> Share your final piece.</li></ol><h3>Common Mistakes to Avoid</h3><ul><li>Run-on sentences — use periods or conjunctions correctly</li><li>Subject-verb agreement — the subject and verb must match in number</li><li>Using very informal language in formal writing</li></ul>",
                ],
            ];

            foreach ($modules as $index => $moduleData) {
                Module::updateOrCreate(
                    ['course_id' => $course->id, 'title' => $moduleData['title']],
                    [
                        'content' => $moduleData['content'],
                        'video_url' => $moduleData['video_url'] ?? null,
                    ]
                );
            }

            // 4. Buat quiz dengan 20 soal
            $quiz = Quiz::updateOrCreate(
                ['course_id' => $course->id, 'title' => 'English Fundamentals Quiz'],
                [
                    'time_limit' => 30,
                    'passing_score' => 70,
                ]
            );

            $questions = $this->getEnglishQuestions();

            foreach ($questions as $q) {
                Question::updateOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'question_text' => $q['question_text'],
                    ],
                    [
                        'type' => $q['type'],
                        'options' => $q['options'],
                        'correct_options' => $q['correct_options'],
                    ]
                );
            }
        });
    }

    private function generateTentorId(): string
    {
        $year = date('Y');
        $last = Tentor::where('unique_id', 'like', "T-{$year}-%")
            ->orderBy('unique_id', 'desc')
            ->lockForUpdate()
            ->value('unique_id');

        if ($last) {
            $num = (int) substr($last, -4) + 1;
        } else {
            $num = 1;
        }

        return 'T-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    private function getEnglishQuestions(): array
    {
        return [
            // --- GRAMMAR (8 questions) ---
            [
                'question_text' => 'She ___ to school every day.',
                'type' => 'single',
                'options' => ['A' => 'go', 'B' => 'goes', 'C' => 'going', 'D' => 'gone'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'They ___ playing football when it started to rain.',
                'type' => 'single',
                'options' => ['A' => 'are', 'B' => 'were', 'C' => 'was', 'D' => 'is'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'I have ___ finished my homework.',
                'type' => 'single',
                'options' => ['A' => 'yet', 'B' => 'since', 'C' => 'already', 'D' => 'for'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'Choose the correct sentence:',
                'type' => 'single',
                'options' => ['A' => 'He don\'t like coffee.', 'B' => 'He doesn\'t likes coffee.', 'C' => 'He doesn\'t like coffee.', 'D' => 'He not like coffee.'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'The book ___ on the table.',
                'type' => 'single',
                'options' => ['A' => 'are', 'B' => 'am', 'C' => 'is', 'D' => 'be'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'She has been studying English ___ three years.',
                'type' => 'single',
                'options' => ['A' => 'since', 'B' => 'for', 'C' => 'at', 'D' => 'in'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => '___ you like some tea?',
                'type' => 'single',
                'options' => ['A' => 'Would', 'B' => 'Do', 'C' => 'Are', 'D' => 'Have'],
                'correct_options' => ['A'],
            ],
            [
                'question_text' => 'If I ___ rich, I would travel the world.',
                'type' => 'single',
                'options' => ['A' => 'am', 'B' => 'was', 'C' => 'were', 'D' => 'be'],
                'correct_options' => ['C'],
            ],
            // --- VOCABULARY (6 questions) ---
            [
                'question_text' => 'What is the synonym of "happy"?',
                'type' => 'single',
                'options' => ['A' => 'sad', 'B' => 'angry', 'C' => 'joyful', 'D' => 'tired'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'The opposite of "difficult" is ___.',
                'type' => 'single',
                'options' => ['A' => 'hard', 'B' => 'easy', 'C' => 'tough', 'D' => 'complex'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'The word "ancient" most nearly means ___.',
                'type' => 'single',
                'options' => ['A' => 'modern', 'B' => 'new', 'C' => 'very old', 'D' => 'broken'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'She was ___ after running 10 kilometers. (very tired)',
                'type' => 'single',
                'options' => ['A' => 'energetic', 'B' => 'exhausted', 'C' => 'excited', 'D' => 'refreshed'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'The word "generous" means ___.',
                'type' => 'single',
                'options' => ['A' => 'selfish', 'B' => 'willing to give', 'C' => 'mean', 'D' => 'lazy'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'Choose the correct spelling:',
                'type' => 'single',
                'options' => ['A' => 'acommodate', 'B' => 'accommodate', 'C' => 'acomodate', 'D' => 'acommodatte'],
                'correct_options' => ['B'],
            ],
            // --- READING COMPREHENSION (4 questions) ---
            [
                'question_text' => 'Read the passage: "The Amazon rainforest is the largest tropical rainforest in the world. It covers about 5.5 million square kilometers and is home to millions of plant and animal species." What is the main idea of this passage?',
                'type' => 'single',
                'options' => ['A' => 'The Amazon is in South America.', 'B' => 'The Amazon rainforest is very large and biodiverse.', 'C' => 'Many animals live in the Amazon.', 'D' => 'The Amazon has many plants.'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'Based on the previous passage, the Amazon rainforest covers approximately ___ square kilometers.',
                'type' => 'single',
                'options' => ['A' => '1.5 million', 'B' => '3 million', 'C' => '5.5 million', 'D' => '10 million'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'Passage: "English is spoken by over 1.5 billion people worldwide. It is the official language of 67 countries and the most commonly studied second language in the world." According to the passage, how many countries have English as an official language?',
                'type' => 'single',
                'options' => ['A' => '27', 'B' => '47', 'C' => '67', 'D' => '87'],
                'correct_options' => ['C'],
            ],
            [
                'question_text' => 'Based on the same passage, why is English widely studied?',
                'type' => 'single',
                'options' => ['A' => 'It is the easiest language.', 'B' => 'It is the most commonly studied second language.', 'C' => 'It is only spoken in the UK.', 'D' => 'It has no grammar rules.'],
                'correct_options' => ['B'],
            ],
            // --- ERROR RECOGNITION (2 questions) ---
            [
                'question_text' => 'Which sentence has a grammatical error?',
                'type' => 'single',
                'options' => ['A' => 'She sings beautifully.', 'B' => 'They goes to the market every Sunday.', 'C' => 'We are learning English now.', 'D' => 'He has finished his work.'],
                'correct_options' => ['B'],
            ],
            [
                'question_text' => 'Which word is misspelled?',
                'type' => 'single',
                'options' => ['A' => 'necessary', 'B' => 'embarrass', 'C' => 'occassion', 'D' => 'committee'],
                'correct_options' => ['C'],
            ],
        ];
    }
}
