@extends('layouts.app')

@section('title', 'Register - DailyTracker')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Brand & Progress -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        
        <!-- Background Pattern -->
        <svg class="absolute inset-0 w-full h-full opacity-10" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g fill="white" fill-opacity="0.4">
                    <circle cx="30" cy="30" r="2"/>
                    <circle cx="10" cy="10" r="1"/>
                    <circle cx="50" cy="10" r="1"/>
                    <circle cx="10" cy="50" r="1"/>
                    <circle cx="50" cy="50" r="1"/>
                </g>
            </g>
        </svg>

        <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
            <div class="max-w-md text-center">
                <!-- Logo -->
                <div class="mx-auto h-20 w-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mb-8 shadow-2xl">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                
                <h1 class="text-5xl font-bold mb-4">DailyTracker</h1>
                <p class="text-xl opacity-90 mb-8">Join thousands of professionals who track their daily progress and achieve their goals.</p>
                
                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div id="left-step1-indicator" class="w-10 h-10 bg-white/30 rounded-full flex items-center justify-center text-white text-sm font-bold transition-all duration-300">
                                1
                            </div>
                            <span id="left-step1-text" class="ml-3 text-lg font-medium">Personal Details</span>
                        </div>
                    </div>
                    <div class="w-16 h-1 bg-white/20 mx-auto my-4">
                        <div id="left-progress-bar" class="h-full bg-white transition-all duration-500 w-0 rounded-full"></div>
                    </div>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div id="left-step2-indicator" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white/60 text-sm font-bold transition-all duration-300">
                                2
                            </div>
                            <span id="left-step2-text" class="ml-3 text-lg font-medium text-white/60">Security Setup</span>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="grid grid-cols-1 gap-4 text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm opacity-90">Track daily habits and goals</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <span class="text-sm opacity-90">Visualize your progress</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm opacity-90">Optimize your time</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Registration Form -->
    <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-md w-full space-y-8">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">DailyTracker</h1>
            </div>

            <!-- Header -->
            <div class="text-center">
                <h2 id="form-title" class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h2>
                <p id="form-subtitle" class="text-gray-600">Let's get started with your personal information</p>
            </div>

            <!-- Progress Indicator (Mobile) -->
            <div class="lg:hidden">
                <div class="flex items-center justify-center space-x-4 mb-6">
                    <div class="flex items-center">
                        <div id="mobile-step1-indicator" class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm text-gray-700 font-medium">Personal</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-200">
                        <div id="mobile-progress-bar" class="h-full bg-blue-500 transition-all duration-500 w-0"></div>
                    </div>
                    <div class="flex items-center">
                        <div id="mobile-step2-indicator" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm text-gray-400 font-medium">Security</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="registration-form" action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- Step 1: Personal Information -->
                <div id="step1" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" required 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-300 @enderror" 
                                   placeholder="Enter your full name" value="{{ old('name') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-300 @enderror" 
                                   placeholder="you@example.com" value="{{ old('email') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <div class="relative">
                            <select id="department" name="department" required 
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('department') border-red-300 @enderror appearance-none bg-white">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <div class="relative">
                            <input id="position" name="position" type="text" required 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('position') border-red-300 @enderror" 
                                   placeholder="Enter your position" value="{{ old('position') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6"/>
                                </svg>
                            </div>
                        </div>
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Button -->
                    <div class="pt-4">
                        <button type="button" id="next-step" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 opacity-50 cursor-not-allowed" disabled>
                            Continue to Security Setup
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Security Information -->
                <div id="step2" class="space-y-4 hidden">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <input id="phone" name="phone" type="tel" 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-300 @enderror" 
                                   placeholder="Enter your phone number" value="{{ old('phone') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-300 @enderror" 
                                   placeholder="Create a strong password">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Confirm your password">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <button type="button" id="prev-step" 
                                class="flex-1 flex justify-center items-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                            </svg>
                            Back
                        </button>
                        <button type="submit" 
                                class="flex-1 flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Account
                        </button>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('next-step');
    const prevBtn = document.getElementById('prev-step');
    const formTitle = document.getElementById('form-title');
    const formSubtitle = document.getElementById('form-subtitle');

    // Desktop indicators
    const leftStep1Indicator = document.getElementById('left-step1-indicator');
    const leftStep2Indicator = document.getElementById('left-step2-indicator');
    const leftStep1Text = document.getElementById('left-step1-text');
    const leftStep2Text = document.getElementById('left-step2-text');
    const leftProgressBar = document.getElementById('left-progress-bar');

    // Mobile indicators
    const mobileStep1Indicator = document.getElementById('mobile-step1-indicator');
    const mobileStep2Indicator = document.getElementById('mobile-step2-indicator');
    const mobileProgressBar = document.getElementById('mobile-progress-bar');

    // Validate step 1 fields
    function validateStep1() {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const department = document.getElementById('department').value;
        const position = document.getElementById('position').value.trim();

        return name && email && department && position;
    }

    // Update next button state
    function updateNextButton() {
        if (validateStep1()) {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Add event listeners to step 1 fields
    ['name', 'email', 'department', 'position'].forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('input', updateNextButton);
        document.getElementById(fieldId).addEventListener('change', updateNextButton);
    });

    // Initial validation
    updateNextButton();

    // Next step
    nextBtn.addEventListener('click', function() {
        if (validateStep1()) {
            // Hide step 1
            step1.classList.add('hidden');
            
            // Show step 2
            step2.classList.remove('hidden');
            step2.classList.add('animate-fade-in');
            
            // Update form title
            formTitle.textContent = 'Security Setup';
            formSubtitle.textContent = 'Create your password and add contact information';
            
            // Update desktop indicators
            if (leftStep1Indicator) {
                leftStep1Indicator.classList.remove('bg-white/30');
                leftStep1Indicator.classList.add('bg-white');
                leftStep1Indicator.innerHTML = '✓';
                
                leftStep2Indicator.classList.remove('bg-white/20', 'text-white/60');
                leftStep2Indicator.classList.add('bg-white/30', 'text-white');
                leftStep2Text.classList.remove('text-white/60');
                leftStep2Text.classList.add('text-white');
                
                leftProgressBar.style.width = '100%';
            }
            
            // Update mobile indicators
            if (mobileStep1Indicator) {
                mobileStep1Indicator.classList.remove('bg-blue-500');
                mobileStep1Indicator.classList.add('bg-green-500');
                mobileStep1Indicator.innerHTML = '✓';
                
                mobileStep2Indicator.classList.remove('bg-gray-200', 'text-gray-400');
                mobileStep2Indicator.classList.add('bg-blue-500', 'text-white');
                
                mobileProgressBar.style.width = '100%';
            }
        }
    });

    // Previous step
    prevBtn.addEventListener('click', function() {
        // Hide step 2
        step2.classList.add('hidden');
        step2.classList.remove('animate-fade-in');
        
        // Show step 1
        step1.classList.remove('hidden');
        step1.classList.add('animate-fade-in');
        
        // Update form title
        formTitle.textContent = 'Create Your Account';
        formSubtitle.textContent = "Let's get started with your personal information";
        
        // Update desktop indicators
        if (leftStep1Indicator) {
            leftStep1Indicator.classList.remove('bg-white');
            leftStep1Indicator.classList.add('bg-white/30');
            leftStep1Indicator.innerHTML = '1';
            
            leftStep2Indicator.classList.remove('bg-white/30', 'text-white');
            leftStep2Indicator.classList.add('bg-white/20', 'text-white/60');
            leftStep2Text.classList.remove('text-white');
            leftStep2Text.classList.add('text-white/60');
            
            leftProgressBar.style.width = '0%';
        }
        
        // Update mobile indicators
        if (mobileStep1Indicator) {
            mobileStep1Indicator.classList.remove('bg-green-500');
            mobileStep1Indicator.classList.add('bg-blue-500');
            mobileStep1Indicator.innerHTML = '1';
            
            mobileStep2Indicator.classList.remove('bg-blue-500', 'text-white');
            mobileStep2Indicator.classList.add('bg-gray-200', 'text-gray-400');
            
            mobileProgressBar.style.width = '0%';
        }
    });
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection