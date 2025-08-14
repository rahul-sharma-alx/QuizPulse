<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizPulse - Interactive Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .floating { animation: float 6s ease-in-out infinite; }
        .text-gradient {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .card-3d {
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        .card-3d:hover {
            transform: rotateY(10deg) rotateX(5deg) translateY(-10px);
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
    </style>
</head>
<body class="font-['Poppins'] bg-gray-900 text-white overflow-x-hidden">
    <!-- 3D Background Canvas -->
    <div id="3d-container" class="fixed inset-0 -z-10 opacity-20"></div>
    
    <!-- Particles -->
    <div id="particles-js"></div>

    <!-- Navigation -->
    <nav class="relative z-50 px-6 py-4 backdrop-blur-md bg-gray-900/80 border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-2xl font-bold text-gradient">QuizPulse</span>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="#features" class="hover:text-purple-400 transition-colors">Features</a>
                <a href="#testimonials" class="hover:text-purple-400 transition-colors">Testimonials</a>
                <a href="#pricing" class="hover:text-purple-400 transition-colors">Pricing</a>
            </div>
            <div class="flex space-x-4">
                <a href="{{url('/login')}}" class="px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">Login</a>
                <a href="{{url('/register')}}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg hover:opacity-90 transition-opacity">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-20">
        <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-12 md:mb-0">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    <span class="text-gradient">Learn</span> Smarter, 
                    <span class="text-gradient">Not</span> Harder
                </h1>
                <p class="text-xl text-gray-300 mb-8 max-w-lg">
                    QuizPulse transforms learning into an engaging experience with interactive quizzes, real-time feedback, and AI-powered recommendations.
                </p>
                <div class="flex space-x-4">
                    <a href="{{url('/register')}}" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg text-lg font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition-all">
                        Get Started
                    </a>
                    <a href="#demo" class="px-8 py-3 border border-gray-700 rounded-lg text-lg font-semibold hover:bg-gray-800/50 transition-colors">
                        Live Demo
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 relative">
                <div class="floating relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-purple-600 to-blue-500 rounded-3xl blur-xl opacity-30"></div>
                    <div class="relative bg-gray-800/50 backdrop-blur-md rounded-2xl overflow-hidden border border-gray-700 p-1">
                        <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb" alt="Quiz Interface" class="rounded-xl w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-900/50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-4">Powerful <span class="text-gradient">Features</span></h2>
            <p class="text-xl text-gray-400 text-center mb-16 max-w-3xl mx-auto">
                Designed to make learning interactive, measurable, and fun
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-3d bg-gray-800/50 backdrop-blur-md rounded-2xl p-8 border border-gray-700 hover:border-purple-500 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-brain text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">AI-Powered Quizzes</h3>
                    <p class="text-gray-400">Our system adapts to your learning style and creates personalized quizzes to maximize knowledge retention.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card-3d bg-gray-800/50 backdrop-blur-md rounded-2xl p-8 border border-gray-700 hover:border-purple-500 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Gamified Learning</h3>
                    <p class="text-gray-400">Earn badges, unlock achievements, and climb leaderboards while mastering new concepts.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card-3d bg-gray-800/50 backdrop-blur-md rounded-2xl p-8 border border-gray-700 hover:border-purple-500 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Real-Time Analytics</h3>
                    <p class="text-gray-400">Track your progress with detailed performance metrics and personalized recommendations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo -->
    <section id="demo" class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/20 to-blue-900/20 -z-10"></div>
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-4">Interactive <span class="text-gradient">Demo</span></h2>
            <p class="text-xl text-gray-400 text-center mb-16 max-w-3xl mx-auto">
                Experience QuizPulse in action with our live demo
            </p>
            
            <div class="bg-gray-800/50 backdrop-blur-md rounded-2xl border border-gray-700 p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/2">
                        <div class="relative h-full min-h-[400px] bg-gray-900 rounded-xl overflow-hidden border border-gray-700">
                            <!-- 3D Quiz Card -->
                            <div id="quiz-card-3d" class="absolute inset-0 flex items-center justify-center">
                                <div class="w-64 h-96 bg-gradient-to-br from-purple-600 to-blue-500 rounded-2xl shadow-2xl transform rotate-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <h3 class="text-2xl font-bold mb-4">Try a Sample Quiz</h3>
                        <p class="text-gray-400 mb-6">Experience how QuizPulse makes learning engaging and effective.</p>
                        
                        <div class="space-y-4">
                            <button class="w-full py-3 px-6 bg-gray-700 hover:bg-gray-600 rounded-lg text-left transition-colors">
                                <span class="font-medium">1.</span> What is the capital of France?
                            </button>
                            <button class="w-full py-3 px-6 bg-gray-700 hover:bg-gray-600 rounded-lg text-left transition-colors">
                                <span class="font-medium">2.</span> Which planet is known as the Red Planet?
                            </button>
                            <button class="w-full py-3 px-6 bg-gray-700 hover:bg-gray-600 rounded-lg text-left transition-colors">
                                <span class="font-medium">3.</span> What is the chemical symbol for Gold?
                            </button>
                        </div>
                        
                        <button class="mt-6 w-full py-3 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition-all">
                            Submit Answers
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-purple-900/40 to-blue-900/40 backdrop-blur-md">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Transform Your Learning?</h2>
            <p class="text-xl text-gray-300 mb-8">Join thousands of learners who are mastering new concepts with QuizPulse.</p>
            <a href="{{url('/register')}}" class="inline-block px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg text-lg font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition-all">
                Start Your Free Trial
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-6 md:mb-0">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <span class="text-2xl font-bold text-gradient">QuizPulse</span>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>© 2023 QuizPulse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- 3D Background Script -->
    <script>
        // Initialize Three.js scene
        const container = document.getElementById('3d-container');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        // Add floating geometry
        const geometry = new THREE.TorusKnotGeometry(10, 3, 100, 16);
        const material = new THREE.MeshBasicMaterial({ 
            color: 0x6366f1, 
            wireframe: true,
            transparent: true,
            opacity: 0.2
        });
        const torusKnot = new THREE.Mesh(geometry, material);
        scene.add(torusKnot);

        camera.position.z = 30;

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            torusKnot.rotation.x += 0.005;
            torusKnot.rotation.y += 0.005;
            renderer.render(scene, camera);
        }
        animate();

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Particles.js configuration
        document.addEventListener('DOMContentLoaded', function() {
            // This would be replaced with actual particles.js initialization
            // For now, we'll just simulate it with the Three.js scene
        });

        // 3D card interaction
        const quizCard = document.getElementById('quiz-card-3d');
        quizCard.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            quizCard.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        quizCard.addEventListener('mouseenter', () => {
            quizCard.style.transition = 'none';
        });

        quizCard.addEventListener('mouseleave', () => {
            quizCard.style.transition = 'all 0.5s ease';
            quizCard.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });
    </script>
</body>
</html>