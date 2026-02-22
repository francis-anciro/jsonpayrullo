import React from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Users, BarChart3, Hexagon, LogOut, Wallet } from 'lucide-react';

const Navbar = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const currentPath = location.pathname;
  
  // Replace these values with your PHP session data later
  const user = {
    name: "John Doe",
    position: "HR Manager"
  };

  // Helper to get initials (e.g., "John Doe" -> "JD")
  const getInitials = (name) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
  };

  const navItems = [
    { name: 'Home', icon: LayoutDashboard, path: '/' },
    { name: 'Employee List', icon: Users, path: '/employees' },
    { name: 'Payroll', icon: Wallet, path: '/payroll' },
    { name: 'Analytics', icon: BarChart3, path: '/analytics' },
  ];

  const isActive = (path) => {
    if (path === '/' && currentPath !== '/') return false;
    return currentPath.startsWith(path);
  };
  
  const handleLogout = () => {
    console.log("Logout triggered! Redirecting to login...");
    // TODO: Add your backend fetch to destroy PHP session here
    
    // Redirects user back to the login page
    navigate('/login'); 
  };

  return (
    <nav className="w-full border-b border-zinc-800/80 bg-[#0a0a0a]/80 backdrop-blur-xl text-white sticky top-0 z-50 transition-all duration-300 shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
      {/* Added 'relative' here so the tabs can be perfectly centered */}
      <div className="flex h-20 w-full items-center justify-between px-6 md:px-10 py-4 relative">

        {/* --- LOGO SECTION --- */}
        <div className="flex items-center gap-3 group cursor-pointer z-10" onClick={() => navigate('/')}>
          <div className="flex items-center justify-center rounded-xl bg-blue-600/10 p-2.5 border border-blue-500/20 group-hover:bg-blue-600/20 group-hover:border-blue-500/40 transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            <Hexagon className="h-6 w-6 text-blue-500 group-hover:scale-110 transition-transform duration-300" />
          </div>
          <span className="text-xl font-black tracking-widest uppercase bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent drop-shadow-md">
            PAYRULLO
          </span>
        </div>

        {/* --- NAVIGATION LINKS (The "Wheel / Pill Dock") --- */}
        {/* Absolute positioning perfectly centers the dock regardless of logo/profile width */}
        <div className="hidden md:flex items-center gap-1 bg-[#000000]/60 border border-zinc-800/80 p-1.5 rounded-full shadow-inner absolute left-1/2 -translate-x-1/2 z-0">
          {navItems.map((item) => {
            const Icon = item.icon; 
            const active = isActive(item.path);
            
            return (
              <Link
                key={item.name}
                to={item.path}
                // Changed rounded-xl to rounded-full to complete the "wheel/pill" look
                className={`group flex items-center gap-2.5 rounded-full px-5 py-2.5 text-xs font-bold tracking-widest uppercase transition-all duration-300 ${
                  active
                    ? 'bg-blue-500/15 text-blue-400 border border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.2)]'
                    : 'text-zinc-400 hover:bg-zinc-800/60 hover:text-white border border-transparent'
                }`}
              >
                <Icon className={`h-4 w-4 ${active ? '' : 'group-hover:-translate-y-0.5'} transition-transform duration-300`} />
                <span>{item.name}</span>
              </Link>
            );
          })}
        </div>

        {/* --- USER INFO & LOGOUT SECTION --- */}
        <div className="flex items-center gap-4 z-10">
          
          {/* User Profile Pill */}
          <div className="hidden sm:flex items-center gap-3 bg-[#121212] border border-zinc-800/80 rounded-full py-1.5 pl-1.5 pr-5 shadow-inner">
            <div className="w-8 h-8 rounded-full bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-black text-xs tracking-widest shadow-[0_0_10px_rgba(59,130,246,0.2)]">
              {getInitials(user.name)}
            </div>
            <div className="flex flex-col">
              <span className="text-xs font-bold text-white leading-tight">{user.name}</span>
              <span className="text-[9px] font-bold uppercase tracking-widest text-blue-400 leading-tight">
                {user.position}
              </span>
            </div>
          </div>
          
          <div className="h-6 w-px bg-zinc-800 hidden sm:block"></div>
          
          {/* Logout Button */}
          <button 
            onClick={handleLogout}
            className="group rounded-xl p-2.5 bg-zinc-900/50 border border-zinc-800/50 text-zinc-400 transition-all duration-300 hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 hover:shadow-[0_0_15px_rgba(239,68,68,0.2)]"
            title="Log out"
          >
            <LogOut className="h-5 w-5 group-hover:translate-x-0.5 transition-transform duration-300" />
          </button>
        </div>

      </div>
    </nav>
  );
};

export default Navbar;