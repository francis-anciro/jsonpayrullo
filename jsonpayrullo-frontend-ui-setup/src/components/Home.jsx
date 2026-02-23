import React, { useState, useEffect } from 'react';
import {
  Clock,
  User,
  Briefcase,
  Building2,
  CircleCheck,
  CircleMinus,
  CalendarClock,
  LayoutDashboard,
  Loader2
} from 'lucide-react';

const Home = () => {
  const [isMounted, setIsMounted] = useState(false);
  const [currentTime, setCurrentTime] = useState(new Date());
  const [data, setData] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [actionLoading, setActionLoading] = useState(false);
  const [message, setMessage] = useState(null);

  useEffect(() => {
    setIsMounted(true);
    const timer = setInterval(() => setCurrentTime(new Date()), 1000);
    fetchData(); // Load initial data
    return () => clearInterval(timer);
  }, []);

  const fetchData = async () => {
    try {
      const response = await fetch('http://localhost/JSONPayrullo/public/home', {
        headers: { 'Accept': 'application/json' },
        credentials: 'include' // CRITICAL for sessions
      });

      if (response.status === 401) {
        // If session expired, you might want to redirect to login
        console.error("Session expired or unauthorized");
        // window.location.href = '/';
      }

      const result = await response.json();
      setData(result);
    } catch (err) {
      console.error("Failed to fetch dashboard data", err);
    } finally {
      setIsLoading(false);
    }
  };

  const handleAttendance = async (type) => {
    setActionLoading(true);
    setMessage(null);
    try {
      const response = await fetch(`http://localhost/JSONPayrullo/public/home/${type}`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({}) // Empty body just in case PHP expects one
      });
      const result = await response.json();

      setMessage({ type: result.status, text: result.response });
      if (result.status === 'success') {
        fetchData(); // Refresh table and UI state
      } else {
        alert(result.response); // Show error if tap in/out fails
      }
    } catch (err) {
      alert('Connection lost while saving attendance.');
    } finally {
      setActionLoading(false);
    }
  };

  // Check if the user is currently "tapped in" by looking at the most recent record
  const isTimedIn = data?.attendanceHistory?.length > 0 && !data.attendanceHistory[0].time_out;

  // Show a loading screen while the initial fetch happens
  if (isLoading) {
    return (
        <div className="h-screen bg-[#060606] flex flex-col items-center justify-center text-zinc-500">
          <Loader2 className="animate-spin mb-4" size={40} />
          <p className="font-bold tracking-widest text-sm uppercase">Loading Dashboard...</p>
        </div>
    );
  }

  return (
      <div className="relative flex flex-col items-center justify-center p-4 md:p-6 min-h-[calc(100vh-4rem)] overflow-hidden bg-[#060606]">

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

          {/* TIGHTER GRID GAP */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {/* LEFT COLUMN: Avatar & Action Button */}
            <div className="flex flex-col gap-5 lg:col-span-1">

              {/* Avatar Card */}
              <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-6 flex items-center justify-center relative overflow-hidden group">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
                <div className={`absolute inset-0 transition-opacity duration-500 ${isTimedIn ? 'bg-green-500/5' : 'bg-transparent'}`}></div>

                <div className={`w-32 h-32 md:w-40 md:h-40 rounded-[1.5rem] bg-gradient-to-br from-[#5bb4d8] to-blue-600 flex items-center justify-center shadow-2xl flex-shrink-0 transition-all duration-500 relative z-10 ${
                    isTimedIn ? 'ring-4 ring-green-500 ring-offset-4 ring-offset-[#0a0a0a] shadow-[0_0_40px_rgba(34,197,94,0.3)]' : 'border border-blue-400/30'
                }`}>
              <span className="text-4xl md:text-5xl font-black text-white tracking-widest drop-shadow-md">
                {data?.username?.charAt(0).toUpperCase() || '?'}
              </span>
                </div>
              </div>

              {/* Time In/Out Card */}
              <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-5 flex flex-col items-center justify-center relative overflow-hidden">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
                <button
                    onClick={() => handleAttendance(isTimedIn ? 'tapOut' : 'tapIn')}
                    disabled={actionLoading}
                    className={`w-full py-4 rounded-xl font-black tracking-[0.2em] text-sm transition-all duration-300 border uppercase relative overflow-hidden ${
                        !isTimedIn
                            ? 'bg-green-500/10 border-green-500 text-green-500 hover:bg-green-500 hover:text-white shadow-[0_0_20px_rgba(34,197,94,0.2)] hover:shadow-[0_0_30px_rgba(34,197,94,0.4)]'
                            : 'bg-red-500/10 border-red-500 text-red-500 hover:bg-red-500 hover:text-white shadow-[0_0_20px_rgba(239,68,68,0.2)] hover:shadow-[0_0_30px_rgba(239,68,68,0.4)]'
                    } ${actionLoading ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-1'}`}
                >
                  {actionLoading ? 'PROCESSING...' : (isTimedIn ? 'TIME OUT' : 'TIME IN')}
                </button>
              </div>
            </div>

            {/* RIGHT COLUMN: Info & Recent Attendance */}
            <div className="flex flex-col gap-5 lg:col-span-2">

              {/* Profile Info Card */}
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
                        {data?.username || 'Loading...'}
                      </h2>
                    </div>

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
                      {data?.role || '---'}
                    </p>
                  </div>

                  <div className="flex items-center gap-3">
                    <Building2 className="text-zinc-500" size={20} />
                    <p className="text-zinc-400 font-bold text-base md:text-lg tracking-widest uppercase">
                      {data?.employee_id ? `EMP ID: ${data.employee_id}` : '---'}
                    </p>
                  </div>
                </div>
              </div>

              {/* Attendance Table Card */}
              <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2rem] p-5 md:p-6 flex flex-col flex-1 relative overflow-hidden">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>

                <div className="flex items-center gap-3 bg-zinc-900/40 border border-zinc-800/80 shadow-inner rounded-xl px-4 py-3 mb-4 w-full">
                  <CalendarClock className="text-blue-500" size={16} />
                  <h2 className="text-[11px] md:text-xs font-black text-zinc-300 uppercase tracking-[0.15em]">
                    Recent Attendance
                  </h2>
                </div>

                <div className="flex flex-col w-full">
                  <div className="w-full">

                    <div className="grid grid-cols-4 gap-2 md:gap-4 px-3 py-3 mb-3 bg-zinc-900/40 border border-zinc-800/80 rounded-xl text-[10px] font-black text-zinc-300 uppercase tracking-[0.15em] shadow-inner items-center">
                      <span className="pl-2">Date</span>
                      <span>Time In</span>
                      <span>Time Out</span>
                      <span>Status</span>
                    </div>

                    <div className="flex flex-col gap-2.5">
                      {data?.attendanceHistory?.length > 0 ? (
                          data.attendanceHistory.map((record, index) => {
                            const isComplete = !!record.time_out;
                            const statusText = isComplete ? 'COMPLETED' : 'ACTIVE';
                            const indicatorColor = !isComplete ? 'bg-green-500' : 'bg-blue-500';

                            return (
                                <div key={index} className="group grid grid-cols-4 gap-2 md:gap-4 items-center bg-[#121212] border border-zinc-800/80 hover:border-blue-500/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(59,130,246,0.15)] transition-all duration-300 rounded-xl p-3 relative overflow-hidden text-xs">

                                  <div className={`absolute left-0 top-0 bottom-0 w-1 ${indicatorColor} opacity-20 group-hover:opacity-100 transition-opacity`}></div>

                                  <div className="flex items-center gap-2.5 pl-2">
                                    <div className="hidden sm:flex w-8 h-8 rounded-lg bg-blue-500/10 items-center justify-center border border-blue-500/20 text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors shadow-inner flex-shrink-0">
                                      <CalendarClock size={14} />
                                    </div>
                                    <span className="font-black text-white tracking-wider drop-shadow-sm truncate">{record.attendance_date}</span>
                                  </div>

                                  <span className="text-zinc-300 font-medium truncate">{record.time_in}</span>
                                  <span className="text-zinc-300 font-medium truncate">{record.time_out || '--:--'}</span>

                                  <div className="flex items-center">
                              <span className={`px-2.5 py-1 rounded-md text-[9px] font-black tracking-[0.1em] uppercase border shadow-inner inline-block truncate ${!isComplete ? 'text-green-400 bg-green-500/10 border-green-500/30' : 'text-zinc-400 bg-zinc-800 border-zinc-700'}`}>
                                {statusText}
                              </span>
                                  </div>

                                </div>
                            );
                          })
                      ) : (
                          <div className="text-center py-8 text-zinc-500 text-xs font-bold tracking-widest uppercase">
                            No attendance records found
                          </div>
                      )}
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