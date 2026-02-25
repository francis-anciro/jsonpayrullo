import React, { useState, useEffect } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Users, BarChart3, Hexagon, LogOut, Wallet } from 'lucide-react';

const Navbar = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const currentPath = location.pathname;

  // 1. Setup state to include role
  const [user, setUser] = useState({
    name: "Loading...",
    position: "---",
    role: "employee" // Added role default
  });

  // 2. Fetch user data from localStorage on component mount
  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      const parsedUser = JSON.parse(storedUser);
      setUser({
        name: parsedUser.username || "Unknown",
        role: parsedUser.role || "employee" // Safely grab the role from the backend
      });
    }
  }, []);

  const getInitials = (name) => {
    if (!name || name === "Loading...") return "?";
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
  };

  // 3. The master list of all tabs
  const allNavItems = [
    { name: 'Home', icon: LayoutDashboard, path: '/' },
    { name: 'Employee List', icon: Users, path: '/employees' },
    { name: 'Payroll', icon: Wallet, path: '/payroll' },
    { name: 'Analytics', icon: BarChart3, path: '/analytics' },
  ];

  // 4. THE IF STATEMENT: Filter based on Admin role
  let navItems = [];
  if (user.role && user.role.toLowerCase() === 'admin') {
    navItems = allNavItems; // Admin sees everything
  } else {
    navItems = [
      { name: 'Home', icon: LayoutDashboard, path: '/' } // Non-admins only see Home
    ];
  }

  const activeTheme = {
    'Home': { text: 'text-blue-400', border: 'border-blue-500/60', bg: 'bg-blue-500', glow: 'shadow-[0_0_10px_rgba(59,130,246,0.8)]', tabGlow: 'shadow-[0_0_25px_rgba(59,130,246,0.2)]' },
    'Employee List': { text: 'text-emerald-400', border: 'border-emerald-500/60', bg: 'bg-emerald-500', glow: 'shadow-[0_0_10px_rgba(16,185,129,0.8)]', tabGlow: 'shadow-[0_0_25px_rgba(16,185,129,0.2)]' },
    'Payroll': { text: 'text-violet-400', border: 'border-violet-500/60', bg: 'bg-violet-500', glow: 'shadow-[0_0_10px_rgba(139,92,246,0.8)]', tabGlow: 'shadow-[0_0_25px_rgba(139,92,246,0.2)]' },
    'Analytics': { text: 'text-amber-400', border: 'border-amber-500/60', bg: 'bg-amber-500', glow: 'shadow-[0_0_10px_rgba(245,158,11,0.8)]', tabGlow: 'shadow-[0_0_25px_rgba(245,158,11,0.2)]' },
  };

  const isActive = (path) => {
    if (path === '/' && currentPath !== '/') return false;
    return currentPath.startsWith(path);
  };

  const handleLogout = () => {
    // Clear the local storage and redirect
    localStorage.removeItem('user');
    navigate('/login');
  };

  return (
      <nav className="w-full text-white sticky top-0 z-50 transition-all duration-300">
        <div className="flex h-20 w-full items-center justify-between px-6 md:px-10 py-4 relative">

          {/* --- LOGO SECTION --- */}
          <div className="flex items-center gap-3 group cursor-pointer z-10" onClick={() => navigate('/')}>
            <div className="flex items-center justify-center rounded-xl bg-blue-600/20 p-2.5 border border-blue-500/40 group-hover:bg-blue-600/30 group-hover:border-blue-500/60 transition-all duration-300 shadow-[0_0_15px_rgba(59,130,246,0.3)]">
              <Hexagon className="h-7 w-7 text-blue-500 group-hover:scale-110 transition-transform duration-300" />
            </div>
            <span className="text-2xl font-black tracking-widest uppercase bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
            PAYRULLO
          </span>
          </div>

          {/* --- NAVIGATION LINKS --- */}
          <div className="hidden md:flex items-center gap-5 absolute left-1/2 -translate-x-1/2 z-0">
            {navItems.map((item) => {
              const Icon = item.icon;
              const active = isActive(item.path);
              const theme = activeTheme[item.name];

              return (
                  <Link
                      key={item.name}
                      to={item.path}
                      className={`group relative flex items-center gap-3 px-5 py-2.5 rounded-2xl border transition-all duration-300 bg-zinc-900 shadow-xl active:scale-95 ${
                          active
                              ? `${theme.border} text-white ${theme.tabGlow}`
                              : 'border-zinc-800 text-white hover:border-zinc-600'
                      }`}
                  >
                    <Icon className={`h-5 w-5 ${active ? `${theme.text} animate-pulse` : 'text-zinc-400 group-hover:text-white'} transition-colors duration-300`} />
                    <span className="text-sm font-black tracking-[0.1em] uppercase">
                  {item.name}
                </span>

                    <span className={`absolute -bottom-1 left-1/2 -translate-x-1/2 h-1 rounded-full transition-all duration-500 ease-out ${
                        active ? `w-1/3 opacity-100 ${theme.bg} ${theme.glow}` : 'w-0 opacity-0'
                    }`} />
                  </Link>
              );
            })}
          </div>

          {/* --- USER INFO & LOGOUT SECTION --- */}
          <div className="flex items-center gap-4 z-10">

            <div className="hidden sm:flex items-center gap-3 py-2 px-4 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl hover:border-zinc-700 transition-colors cursor-default">
              <div className="w-9 h-9 rounded-full bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-black text-xs tracking-widest shadow-inner">
                {getInitials(user.name)}
              </div>
              <div className="flex flex-col">
                <span className="text-xs font-black text-white leading-tight uppercase tracking-wider">{user.name}</span>
              </div>
            </div>

            <div className="h-6 w-px bg-zinc-800 hidden sm:block"></div>

            <button
                onClick={handleLogout}
                className="group p-3 rounded-2xl bg-zinc-900 border border-zinc-800 text-white transition-all duration-300 hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/50 hover:shadow-[0_0_15px_rgba(239,68,68,0.1)] active:scale-90 shadow-xl"
                title="Log out"
            >
              <LogOut className="h-6 w-6 group-hover:translate-x-0.5 transition-transform duration-300" />
            </button>
          </div>

        </div>
      </nav>
  );
};

export default Navbar;