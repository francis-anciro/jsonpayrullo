import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom'; // Added for redirect
import { FiMail, FiLock, FiLoader, FiLogIn, FiHexagon } from 'react-icons/fi';

const Login = () => {
  const [isMounted, setIsMounted] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState(null);

  const navigate = useNavigate(); // Initialize navigation

  useEffect(() => {
    setIsMounted(true);
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setError(null);

    try {
      // Ensure this matches your local XAMPP project folder name
      const API_URL = 'http://localhost/JSONPayrullo/public/login/auth';

// In Login.jsx - handleSubmit
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        credentials: 'include', // MUST be here to save the cookie
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (data.status === 'success') {
        console.log('Login success:', data.user);

        // 1. Store user data so other components know we are logged in
        localStorage.setItem('user', JSON.stringify(data.user));

        // 2. Redirect to the React Home route
        navigate('/');
      } else {
        // 3. Capture the 'response' message sent by PHP handleResponse()
        setError(data.response || 'Invalid credentials');
      }

    } catch (err) {
      setError('Failed to connect to server. Ensure XAMPP/Apache is running.');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="w-screen h-screen flex items-center justify-center relative bg-[#0a0a0a] text-white overflow-hidden font-sans">

      <div className="absolute top-8 left-8 flex items-center gap-2 z-20">
        <FiHexagon className="text-blue-500 text-2xl" />
        <span className="font-bold text-lg tracking-tight text-gray-200">PAYRULLO</span>
      </div>

      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-blue-900/30 to-transparent blur-3xl pointer-events-none transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
      <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-blue-900/30 to-transparent blur-3xl pointer-events-none transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      <div className={`relative z-10 w-full max-w-6xl px-6 flex flex-col md:flex-row items-center justify-center gap-12 md:gap-20 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>

        <div className="text-center md:text-left flex-1">
          <h1 className="text-5xl md:text-6xl font-bold mb-6 leading-tight tracking-tighter">
            Welcome to <br />
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-400">
              JSON Payrullo
            </span>
          </h1>
          <p className="text-gray-400 text-lg mb-8 max-w-md mx-auto md:mx-0">
            HR Management and Payroll System
          </p>
          <p className="text-blue-500 text-xs font-bold tracking-widest uppercase">
            Structured People. Structured Payroll.
          </p>
        </div>

        <div className="w-full md:w-[500px] bg-[#121212] px-10 py-16 md:px-12 md:py-20 rounded-3xl shadow-2xl border border-white/5 border-l-8 border-l-blue-600 backdrop-blur-sm">
          <div className="flex items-center mb-10 space-x-3">
            <div className="p-3 bg-blue-600/10 rounded-xl">
              <FiLogIn className="text-blue-500" size={24} />
            </div>
            <h2 className="text-2xl font-bold text-white tracking-tight">Sign In</h2>
          </div>

          {error && (
            <div className="mb-6 p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm text-center">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            <div className="mb-8">
              <label htmlFor="email" className="block text-sm font-medium text-gray-200 mb-2">Email Address</label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <FiMail className="text-gray-500 group-focus-within:text-blue-500 transition-colors" />
                </div>
                <input
                  type="email"
                  id="email"
                  className="w-full pl-11 pr-4 py-3.5 bg-[#0a0a0a] border border-white/10 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none text-white placeholder-gray-600"
                  placeholder="name@example.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>
            </div>

            <div className="mb-10">
              <label htmlFor="password" className="block text-sm font-medium text-gray-200 mb-2">Password</label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <FiLock className="text-gray-500 group-focus-within:text-blue-500 transition-colors" />
                </div>
                <input
                  type="password"
                  id="password"
                  className="w-full pl-11 pr-4 py-3.5 bg-[#0a0a0a] border border-white/10 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none text-white placeholder-gray-600"
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={isLoading}
              className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-600/20"
            >
              {isLoading ? <FiLoader className="animate-spin mr-2" size={20} /> : 'Sign In'}
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Login;