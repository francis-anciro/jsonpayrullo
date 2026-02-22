import React, { useState, useEffect } from 'react';
import { Clock, User, Briefcase, Building2, CircleCheck, CircleMinus, CalendarClock, LayoutDashboard } from 'lucide-react';

const Home = () => {
  const [isMounted, setIsMounted] = useState(false);
  const [currentTime, setCurrentTime] = useState(new Date());

  useEffect(() => {
    setIsMounted(true);
    // Live clock timer
    const timer = setInterval(() => setCurrentTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  const [employeeData, setEmployeeData] = useState({
    initials: 'ZE',
    name: 'ZEIT',
    role: 'ADMIN',
    department: 'GENERAL DEPT',
  });

  // 5 Recent Attendance Records
  const [recentAttendance, setRecentAttendance] = useState([
    { id: 1, date: '2026-02-21', time_in: '08:05 AM', time_out: '05:00 PM', status: 'Present' },
    { id: 2, date: '2026-02-20', time_in: '08:00 AM', time_out: '05:15 PM', status: 'Present' },
    { id: 3, date: '2026-02-19', time_in: '09:30 AM', time_out: '06:00 PM', status: 'Late' },
    { id: 4, date: '2026-02-18', time_in: '-', time_out: '-', status: 'Absent' },
    { id: 5, date: '2026-02-17', time_in: '07:55 AM', time_out: '05:00 PM', status: 'Present' },
  ]);

  const [isTimedIn, setIsTimedIn] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const handleTimeIn = async () => {
    setIsLoading(true);
    try {
      console.log("Sending Time In data to server...");
      await new Promise(resolve => setTimeout(resolve, 600)); 
      setIsTimedIn(true);
    } catch (error) {
      console.error("Failed to time in:", error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleTimeOut = async () => {
    setIsLoading(true);
    try {
      console.log("Sending Time Out data to server...");
      await new Promise(resolve => setTimeout(resolve, 600)); 
      setIsTimedIn(false);
    } catch (error) {
      console.error("Failed to time out:", error);
    } finally {
      setIsLoading(false);
    }
  };

  const getStatusStyle = (status) => {
    switch (status) {
      case 'Present': return 'border-green-500 text-green-500 bg-green-500/10';
      case 'Late': return 'border-yellow-500 text-yellow-500 bg-yellow-500/10';
      case 'Absent': return 'border-red-500 text-red-500 bg-red-500/10';
      default: return 'border-zinc-500 text-zinc-500 bg-zinc-500/10';
    }
  };

  return (
    <div className="relative flex flex-col items-center justify-center p-4 md:p-6 min-h-[calc(100vh-4rem)] overflow-hidden">
      
      {/* --- BACKGROUND LAYER --- */}
      <div 
        className={`fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50`}
        style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}
      ></div>
      
      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-blue-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-all duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
      <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-blue-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-all duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      {/* --- MAIN CONTENT --- */}
      <div className={`relative z-10 w-full max-w-5xl flex flex-col transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>
        
        {/* Header & Live Clock */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-end w-full mb-4 px-2 gap-3">
          <h1 className="text-xl md:text-2xl font-black text-white tracking-wide uppercase drop-shadow-lg flex items-center gap-3">
            <LayoutDashboard className="text-blue-500" size={28} /> My Dashboard
          </h1>
          <div className="flex items-center gap-3 bg-[#0a0a0a]/80 backdrop-blur-md border border-zinc-800 rounded-xl px-4 py-2.5 shadow-lg">
            <Clock className="text-blue-500" size={18} />
            <span className="text-lg font-bold tracking-widest text-zinc-200">
              {currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
            </span>
          </div>
        </div>

        {/* TIGHTER GRID GAP (gap-5 instead of gap-6) */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">
          
          {/* LEFT COLUMN: Avatar & Action Button */}
          <div className="flex flex-col gap-5 lg:col-span-1">
            
            {/* Avatar Card - Reduced Padding and Size */}
            <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-6 flex items-center justify-center relative overflow-hidden group">
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
              <div className={`absolute inset-0 transition-opacity duration-500 ${isTimedIn ? 'bg-green-500/5' : 'bg-transparent'}`}></div>
              
              <div className={`w-32 h-32 md:w-40 md:h-40 rounded-[1.5rem] bg-gradient-to-br from-[#5bb4d8] to-blue-600 flex items-center justify-center shadow-2xl flex-shrink-0 transition-all duration-500 relative z-10 ${
                isTimedIn ? 'ring-4 ring-green-500 ring-offset-4 ring-offset-[#0a0a0a] shadow-[0_0_40px_rgba(34,197,94,0.3)]' : 'border border-blue-400/30'
              }`}>
                <span className="text-4xl md:text-5xl font-black text-white tracking-widest drop-shadow-md">
                  {employeeData.initials}
                </span>
              </div>
            </div>

            {/* Time In/Out Card - Reduced Padding */}
            <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-5 flex flex-col items-center justify-center relative overflow-hidden">
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
              <button
                onClick={isTimedIn ? handleTimeOut : handleTimeIn}
                disabled={isLoading}
                className={`w-full py-4 rounded-xl font-black tracking-[0.2em] text-sm transition-all duration-300 border uppercase relative overflow-hidden ${
                  !isTimedIn 
                    ? 'bg-green-500/10 border-green-500 text-green-500 hover:bg-green-500 hover:text-white shadow-[0_0_20px_rgba(34,197,94,0.2)] hover:shadow-[0_0_30px_rgba(34,197,94,0.4)]' 
                    : 'bg-red-500/10 border-red-500 text-red-500 hover:bg-red-500 hover:text-white shadow-[0_0_20px_rgba(239,68,68,0.2)] hover:shadow-[0_0_30px_rgba(239,68,68,0.4)]'
                } ${isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-1'}`}
              >
                {isLoading ? 'PROCESSING...' : (isTimedIn ? 'TIME OUT' : 'TIME IN')}
              </button>
            </div>
          </div>

          {/* RIGHT COLUMN: Info & Recent Attendance */}
          <div className="flex flex-col gap-5 lg:col-span-2">
            
            {/* Info Card - Reduced Padding and Font Sizes */}
            <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-6 md:p-8 flex flex-col justify-center relative overflow-hidden">
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
              
              <div className="absolute -right-10 -bottom-10 opacity-[0.03] pointer-events-none">
                <svg width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1" strokeLinecap="round" strokeLinejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
              </div>

              <div className="flex flex-col gap-4 w-full relative z-10">
                <div className="border-b border-zinc-800 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                  <div className="flex items-center gap-3">
                    <div className="p-2.5 bg-zinc-800/50 rounded-xl hidden sm:block">
                      <User className="text-zinc-400" size={24} />
                    </div>
                    <h2 className="text-2xl md:text-4xl font-black text-white uppercase tracking-wider">
                      {employeeData.name}
                    </h2>
                  </div>
                  
                  {/* Glowing Status Badge */}
                  <div className={`flex items-center gap-2 px-3 py-1.5 rounded-full border border-zinc-800 bg-[#121212] shadow-inner`}>
                    {isTimedIn ? <CircleCheck className="text-green-500 animate-pulse" size={14} /> : <CircleMinus className="text-zinc-500" size={14} />}
                    <span className={`text-[10px] md:text-xs font-bold tracking-widest uppercase ${isTimedIn ? 'text-green-500' : 'text-zinc-500'}`}>
                      {isTimedIn ? 'On The Clock' : 'Off The Clock'}
                    </span>
                  </div>
                </div>

                <div className="border-b border-zinc-800 pb-4 flex items-center gap-3">
                  <Briefcase className="text-blue-500" size={20} />
                  <p className="text-blue-400 font-bold text-base md:text-lg tracking-widest uppercase">
                    {employeeData.role}
                  </p>
                </div>

                <div className="flex items-center gap-3">
                  <Building2 className="text-zinc-500" size={20} />
                  <p className="text-zinc-400 font-bold text-base md:text-lg tracking-widest uppercase">
                    {employeeData.department}
                  </p>
                </div>
              </div>
            </div>

            {/* Recent Attendance Card - Reduced Padding and Row Heights */}
            <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-5 md:p-6 flex flex-col flex-1 relative overflow-hidden">
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
              
              {/* Recessed Pill Header */}
              <div className="flex items-center gap-3 bg-zinc-900/40 border border-zinc-800/80 shadow-inner rounded-xl px-4 py-3 mb-4 w-full">
                <CalendarClock className="text-blue-500" size={16} />
                <h2 className="text-[11px] md:text-xs font-black text-zinc-300 uppercase tracking-[0.15em]">
                  Recent Attendance
                </h2>
              </div>
              
              <div className="flex flex-col w-full">
                <div className="w-full">
                  
                  {/* Upgraded Table Header */}
                  <div className="grid grid-cols-4 gap-2 md:gap-4 px-3 py-3 mb-3 bg-zinc-900/40 border border-zinc-800/80 rounded-xl text-[10px] font-black text-zinc-300 uppercase tracking-[0.15em] shadow-inner items-center">
                    <span className="pl-2">Date</span>
                    <span>Time In</span>
                    <span>Time Out</span>
                    <span>Status</span>
                  </div>
                  
                  {/* Upgraded Table Rows ("FIRE" Design) - Tighter Gaps and Padding */}
                  <div className="flex flex-col gap-2.5">
                    {recentAttendance.map((record) => (
                      <div key={record.id} className="group grid grid-cols-4 gap-2 md:gap-4 items-center bg-[#121212] border border-zinc-800/80 hover:border-blue-500/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(59,130,246,0.15)] transition-all duration-300 rounded-xl p-3 relative overflow-hidden text-xs">
                        
                        {/* Subtle Left Border Glow */}
                        <div className={`absolute left-0 top-0 bottom-0 w-1 ${record.status === 'Present' ? 'bg-green-500' : record.status === 'Late' ? 'bg-yellow-500' : 'bg-red-500'} opacity-20 group-hover:opacity-100 transition-opacity`}></div>

                        <div className="flex items-center gap-2.5 pl-2">
                          <div className="hidden sm:flex w-8 h-8 rounded-lg bg-blue-500/10 items-center justify-center border border-blue-500/20 text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors shadow-inner flex-shrink-0">
                            <CalendarClock size={14} />
                          </div>
                          <span className="font-black text-white tracking-wider drop-shadow-sm truncate">{record.date}</span>
                        </div>

                        <span className="text-zinc-300 font-medium truncate">{record.time_in}</span>
                        <span className="text-zinc-300 font-medium truncate">{record.time_out}</span>

                        <div className="flex items-center">
                          <span className={`px-2.5 py-1 rounded-md text-[9px] font-black tracking-[0.1em] uppercase border shadow-inner ${getStatusStyle(record.status)} inline-block truncate`}>
                            {record.status}
                          </span>
                        </div>

                      </div>
                    ))}
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;