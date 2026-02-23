import React, { useState, useEffect } from 'react';
import { ArrowLeft, Loader2, CalendarDays } from 'lucide-react';
import { Link, useParams } from 'react-router-dom';

const Attendance = () => {
  const { id } = useParams();
  const [isMounted, setIsMounted] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  
  const [employeeInfo, setEmployeeInfo] = useState({});
  const [attendanceRecords, setAttendanceRecords] = useState([]);

  useEffect(() => {
    setIsMounted(true);

    const fetchAttendanceData = async () => {
      try {
        console.log(`Fetching full attendance for Employee ID: ${id}`);
        // TODO: Replace with real backend fetch
        // const res = await fetch(`/api/attendance.php?id=${id}`);
        // const data = await res.json();
        // setEmployeeInfo(data.employee);
        // setAttendanceRecords(data.records);

        await new Promise(resolve => setTimeout(resolve, 800)); // Simulate network

        setEmployeeInfo({
          first_name: 'John',
          last_name: 'Doe',
          employee_code: 'EMP-001'
        });

        // Dummy Data - Full History
        setAttendanceRecords([
          { id: 1, date: '2026-02-21', time_in: '08:05 AM', time_out: '05:00 PM', hours: '8.9', status: 'Present' },
          { id: 2, date: '2026-02-20', time_in: '08:00 AM', time_out: '05:15 PM', hours: '9.2', status: 'Present' },
          { id: 3, date: '2026-02-19', time_in: '09:30 AM', time_out: '06:00 PM', hours: '8.5', status: 'Late' },
          { id: 4, date: '2026-02-18', time_in: '-', time_out: '-', hours: '0.0', status: 'Absent' },
          { id: 5, date: '2026-02-17', time_in: '08:00 AM', time_out: '05:00 PM', hours: '9.0', status: 'Present' },
          { id: 6, date: '2026-02-16', time_in: '08:10 AM', time_out: '05:00 PM', hours: '8.8', status: 'Present' },
          { id: 7, date: '2026-02-15', time_in: '07:55 AM', time_out: '05:30 PM', hours: '9.5', status: 'Present' },
        ]);

      } catch (error) {
        console.error("Failed to fetch attendance:", error);
      } finally {
        setIsLoading(false);
      }
    };

    fetchAttendanceData();
  }, [id]);

  const getStatusStyle = (status) => {
    switch (status) {
      case 'Present': return 'border-green-500 text-green-500 bg-green-500/10';
      case 'Late': return 'border-yellow-500 text-yellow-500 bg-yellow-500/10';
      case 'Absent': return 'border-red-500 text-red-500 bg-red-500/10';
      default: return 'border-zinc-500 text-zinc-500 bg-zinc-500/10';
    }
  };

  return (
    <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)]">
      
      {/* Background Lights (Indigo for Attendance) */}
      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-indigo-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
      <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-indigo-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      <div className={`relative z-10 w-full max-w-5xl flex flex-col gap-6 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>
        
        {/* HEADER */}
        <div className="flex items-center gap-4 mb-2">
          <Link to="/employees" className="p-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors bg-[#121212] border border-zinc-800 shadow-lg">
            <ArrowLeft size={24} />
          </Link>
          <div className="flex flex-col">
            <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
              Attendance Records
            </h1>
            {!isLoading && (
              <p className="text-indigo-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
                {employeeInfo.first_name} {employeeInfo.last_name} | {employeeInfo.employee_code}
              </p>
            )}
          </div>
        </div>

        {/* MAIN CONTAINER */}
        <div className="bg-[#0a0a0a]/80 backdrop-blur-sm border border-zinc-700 rounded-[2rem] p-6 md:p-8 shadow-2xl flex flex-col gap-6 min-h-[400px]">
          
          {isLoading ? (
            <div className="flex flex-col items-center justify-center flex-1 gap-4">
              <Loader2 className="text-indigo-500 animate-spin" size={48} />
              <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Loading Records...</p>
            </div>
          ) : (
            <div className="w-full overflow-x-auto">
              <div className="min-w-[600px]">
                
                {/* Table Header */}
                <div className="grid grid-cols-5 gap-4 pb-4 border-b border-zinc-800 text-xs font-bold text-zinc-500 uppercase tracking-wider">
                  <span>Date</span>
                  <span>Time In</span>
                  <span>Time Out</span>
                  <span>Total Hours</span>
                  <span>Status</span>
                </div>
                
                {/* Table Body */}
                <div className="flex flex-col">
                  {attendanceRecords.length === 0 ? (
                    <div className="py-10 text-center text-zinc-500 font-bold uppercase tracking-widest">
                      No attendance records found.
                    </div>
                  ) : (
                    attendanceRecords.map((record) => (
                      <div key={record.id} className="grid grid-cols-5 gap-4 py-4 border-b border-zinc-800/50 items-center text-sm transition-colors hover:bg-zinc-800/20 px-2 rounded-lg">
                        <span className="font-bold text-white tracking-wide flex items-center gap-2">
                          <CalendarDays size={16} className="text-indigo-500" />
                          {record.date}
                        </span>
                        <span className="text-zinc-300 font-medium">{record.time_in}</span>
                        <span className="text-zinc-300 font-medium">{record.time_out}</span>
                        <span className="text-zinc-300 font-bold">{record.hours} hrs</span>
                        <span>
                          <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase border ${getStatusStyle(record.status)}`}>
                            {record.status}
                          </span>
                        </span>
                      </div>
                    ))
                  )}
                </div>

              </div>
            </div>
          )}

        </div>
      </div>
    </div>
  );
};

export default Attendance;