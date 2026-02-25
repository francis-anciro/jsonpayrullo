import React, { useState, useEffect } from 'react';
import { Search, Plus, Settings, Loader2, UserMinus, AlertTriangle, X, FileSearch, ChevronDown, ChevronUp, Calendar, CalendarDays, Users, CalendarClock, CalendarMinus, CalendarCheck, History } from 'lucide-react';
import { Link } from 'react-router-dom';

// -----------------------------------------------------
// REUSABLE VIEW FIELD (For the View Modal)
// -----------------------------------------------------
const ViewField = ({ label, value }) => (
    <div className="flex flex-col gap-2">
      <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">{label}</label>
      <div className="w-full px-4 py-3 bg-zinc-900/40 border border-zinc-800/80 rounded-xl text-white min-h-[46px] flex items-center shadow-inner">
        {value || <span className="text-zinc-600">-</span>}
      </div>
    </div>
);

const EmployeeList = () => {
  // -----------------------------------------------------
  // STATE
  // -----------------------------------------------------
  const [isMounted, setIsMounted] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');
  const [isSearching, setIsSearching] = useState(false);
  const [isResigning, setIsResigning] = useState(null);
  const [isMarkingLeave, setIsMarkingLeave] = useState(null);

  // DATA STATE
  const [masterEmployees, setMasterEmployees] = useState([]);
  const [employees, setEmployees] = useState([]);
  const [isFetchingList, setIsFetchingList] = useState(true);

  // RESIGN MODAL STATE
  const [showResignModal, setShowResignModal] = useState(false);
  const [employeeToResign, setEmployeeToResign] = useState(null);

  // VIEW MODAL STATE
  const [showViewModal, setShowViewModal] = useState(false);
  const [employeeToView, setEmployeeToView] = useState(null);
  const [isFetchingDetails, setIsFetchingDetails] = useState(false);

  // ATTENDANCE DROPDOWN STATE
  const [showAttendance, setShowAttendance] = useState(false);
  const [attendanceRecords, setAttendanceRecords] = useState([]);
  const [isFetchingAttendance, setIsFetchingAttendance] = useState(false);

  useEffect(() => {
    setIsMounted(true);
    fetchEmployees();
  }, []);

  const fetchEmployees = async () => {
    try {
      const response = await fetch('http://localhost/JSONPayrullo/public/EmployeeList', {
        headers: { 'Accept': 'application/json' },
        credentials: 'include'
      });

      const data = await response.json();
      console.log("Fetched Data:", data);

      const rawUsers = data.users || (Array.isArray(data) ? data : []);

      if (rawUsers.length > 0) {
        const mappedUsers = rawUsers.map(u => ({
          id: u.User_ID || u.id,
          employee_code: u.employee_code || 'N/A',
          first_name: u.first_name || u.username || 'Unknown',
          mid_name: u.middle_name || '',
          last_name: u.last_name || '',
          role: u.role || 'Employee',
          department: u.department_name || 'General',
          phone: u.phone || 'N/A',
          address: u.address || 'N/A',
          birthdate: u.birthdate || 'N/A',
          hiredate: u.hire_date || 'N/A',
          email: u.email || 'N/A',
          username: u.username || 'N/A',
          // UPDATED: Now maps directly from employment_status (active, on_leave, resigned)
          status: u.employment_status || 'active',
          basic_salary: u.basic_salary || '0.00',
          attendance_history: u.attendance_history || []
        }));

        setMasterEmployees(mappedUsers);
        setEmployees(mappedUsers);
      } else {
        setMasterEmployees([]);
        setEmployees([]);
      }
    } catch (err) {
      console.error("Failed to fetch employees:", err);
    } finally {
      setIsFetchingList(false);
    }
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      setIsSearching(true);

      let filteredList = masterEmployees;

      // 1. Apply Status Filter
      if (statusFilter !== 'all') {
        filteredList = filteredList.filter(emp => {
          // Normalize handles 'on_leave' to 'on-leave' to match filter ID
          const normalizedStatus = emp.status.toLowerCase().replace('_', '-');
          if (statusFilter === 'active') {
            return normalizedStatus === 'present' || normalizedStatus === 'active';
          }
          return normalizedStatus === statusFilter;
        });
      }

      // 2. Apply Search Query
      if (searchQuery.trim() !== '') {
        const lowerCaseQuery = searchQuery.toLowerCase();
        filteredList = filteredList.filter(emp =>
            (emp.first_name && emp.first_name.toLowerCase().includes(lowerCaseQuery)) ||
            (emp.last_name && emp.last_name.toLowerCase().includes(lowerCaseQuery)) ||
            (emp.employee_code && emp.employee_code.toLowerCase().includes(lowerCaseQuery))
        );
      }

      setEmployees(filteredList);
      setIsSearching(false);
    }, 500);

    return () => clearTimeout(delayDebounceFn);
  }, [searchQuery, statusFilter, masterEmployees]);


  const toggleLeaveStatus = async (emp) => {
    setIsMarkingLeave(emp.id);

    // 1. Determine the next status based on current state
    const nextStatus = emp.status === 'on_leave' ? 'active' : 'on_leave';
    const code = emp.employee_code;

    try {
      const response = await fetch(`http://localhost/JSONPayrullo/EmployeeList/toggleLeave/${code}`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: nextStatus }),
        credentials: 'include'
      });

      const result = await response.json();

      if (response.ok && result.status === 'success') {
        const updateList = (list) => list.map(e => e.id === emp.id ? { ...e, status: nextStatus } : e);

        setMasterEmployees(prev => updateList(prev));
        setEmployees(prev => updateList(prev));
      } else {
        alert(result.response || "Failed to update leave status");
      }
    } catch (error) {
      console.error("Toggle Leave Error:", error);
      alert("Network error. Please check your connection.");
    } finally {
      setIsMarkingLeave(null);
    }
  };

  const openResignModal = (emp) => {
    setEmployeeToResign(emp);
    setShowResignModal(true);
  };

  const closeResignModal = () => {
    setShowResignModal(false);
    setEmployeeToResign(null);
  };

  const confirmResign = async () => {
    if (!employeeToResign) return;

    const code = employeeToResign.employee_code;
    setIsResigning(employeeToResign.id);
    setShowResignModal(false);

    try {
      const response = await fetch(`http://localhost/JSONPayrullo/EmployeeList/delete/${code}`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        credentials: 'include'
      });

      const result = await response.json();

      if (response.ok && result.status === 'success') {
        const updateList = (list) => list.map(emp => emp.employee_code === code ? { ...emp, status: 'resigned' } : emp);
        setMasterEmployees(prev => updateList(prev));
        setEmployees(prev => updateList(prev));
      } else {
        alert(result.response || "Failed to resign employee");
      }
    } catch (error) {
      console.error("Resign Error:", error);
    } finally {
      setIsResigning(null);
    }
  };

  const openViewModal = async (emp) => {
    setIsFetchingDetails(true);
    setEmployeeToView(emp);
    setShowViewModal(true);

    setShowAttendance(false);
    setAttendanceRecords([]);

    try {
      await new Promise(resolve => setTimeout(resolve, 300));
    } catch (error) {
      console.error("Error fetching full details:", error);
    } finally {
      setIsFetchingDetails(false);
    }
  };

  const closeViewModal = () => {
    setShowViewModal(false);
    setEmployeeToView(null);
    setShowAttendance(false);
  };

  const toggleAttendance = (empId) => {
    if (showAttendance) {
      setShowAttendance(false);
      return;
    }

    const emp = employees.find(e => e.id === empId);

    if (emp && emp.attendance_history) {
      const formattedRecords = emp.attendance_history.slice(0, 5).map(record => ({
        id: record.Attendance_ID,
        attendance_date: record.attendance_date,
        time_in: record.time_in,
        time_out: record.time_out || '--:--',
        worked_hours: record.worked_hours,
        status: record.status
      }));

      setAttendanceRecords(formattedRecords);
      setShowAttendance(true);
    } else {
      setAttendanceRecords([]);
      setShowAttendance(true);
    }
  };

  const getStatusStyle = (status) => {
    const normalizedStatus = status.toLowerCase().replace('_', '-');
    switch (normalizedStatus) {
      case 'present':
      case 'active':
        return 'border-green-500 text-green-500 bg-green-500/10';
      case 'on-leave':
        return 'border-yellow-500 text-yellow-500 bg-yellow-500/10';
      case 'absent':
        return 'border-red-500 text-red-500 bg-red-500/10';
      case 'resigned':
        return 'border-zinc-500 text-zinc-500 bg-zinc-500/10';
      case 'late':
        return 'border-yellow-500 text-yellow-500 bg-yellow-500/10';
      default:
        return 'border-zinc-500 text-zinc-500 bg-zinc-500/10';
    }
  };

  return (
      <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)]">

        {/* --- BACKGROUND LAYER --- */}
        <div
            className={`fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50`}
            style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}
        ></div>

        {/* BACKGROUND LIGHTS */}
        <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-emerald-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
        <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-emerald-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

        <div className={`relative z-10 w-full max-w-5xl flex flex-col gap-6 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>

          {/* HEADER */}
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
            <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg flex items-center gap-3">
              <Users className="text-emerald-500" size={32} /> Employee List
            </h1>
            <Link
                to="/employees/add"
                className="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-lg shadow-blue-600/20 border border-blue-500"
            >
              <Plus size={18} /> Add Employee
            </Link>
          </div>

          {/* --- UPGRADED LIST CONTAINER --- */}
          <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2.5rem] p-6 md:p-8 flex flex-col gap-6 relative overflow-hidden">

            {/* Subtle top edge glow for depth */}
            <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>

            {/* SEARCH & FILTER BAR WRAPPER */}
            <div className="flex flex-col lg:flex-row gap-4 w-full relative">
              <div className="w-full relative flex-1">
                <div className="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                  {isSearching ? (
                      <Loader2 className="text-emerald-500 animate-spin" size={20} />
                  ) : (
                      <Search className="text-zinc-500" size={20} />
                  )}
                </div>
                <input
                    type="text"
                    className="w-full pl-14 pr-6 py-4 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all outline-none text-white placeholder-zinc-500 shadow-inner"
                    placeholder="Search employees by name or code..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>

              {/* STATUS FILTER BUTTONS */}
              <div className="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-hide flex-shrink-0">
                {[
                  { id: 'all', label: 'All' },
                  { id: 'active', label: 'Active' },
                  { id: 'on-leave', label: 'On Leave' },
                  { id: 'resigned', label: 'Resigned' }
                ].map(filter => (
                    <button
                        key={filter.id}
                        onClick={() => setStatusFilter(filter.id)}
                        className={`px-5 py-4 rounded-2xl border text-xs font-black tracking-widest uppercase transition-all whitespace-nowrap shadow-inner ${
                            statusFilter === filter.id
                                ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]'
                                : 'bg-zinc-900/40 border-zinc-800/80 text-zinc-500 hover:text-white hover:border-zinc-600'
                        }`}
                    >
                      {filter.label}
                    </button>
                ))}
              </div>
            </div>

            {/* EMPLOYEE CARDS */}
            <div className="flex flex-col gap-5 min-h-[400px]">
              {isFetchingList ? (
                  <div className="flex flex-col items-center justify-center py-20 gap-4">
                    <Loader2 className="text-emerald-500 animate-spin" size={40} />
                    <span className="text-zinc-500 font-bold uppercase tracking-widest text-sm">Loading List...</span>
                  </div>
              ) : employees.length === 0 && !isSearching ? (
                  <div className="text-center py-10 text-zinc-500 font-bold uppercase tracking-widest">
                    No employees found.
                  </div>
              ) : (
                  /* --- UPGRADED TABLE ROWS ("FIRE" DESIGN) --- */
                  employees.map((emp) => {
                    const normalizedStatus = emp.status.toLowerCase().replace('_', '-');

                    return (
                        <div key={emp.id} className={`group bg-[#121212] border border-zinc-800/80 ${normalizedStatus === 'resigned' ? 'opacity-60' : 'hover:border-emerald-500/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(16,185,129,0.15)]'} transition-all duration-300 rounded-2xl p-6 relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6`}>

                          {/* Subtle Left Border Glow based on status */}
                          <div className={`absolute left-0 top-0 bottom-0 w-1 ${normalizedStatus === 'present' || normalizedStatus === 'active' ? 'bg-green-500' : normalizedStatus === 'on-leave' ? 'bg-yellow-500' : normalizedStatus === 'absent' ? 'bg-red-500' : 'bg-zinc-500'} opacity-20 group-hover:opacity-100 transition-opacity`}></div>

                          <div className="flex flex-col md:flex-row items-start md:items-center gap-6 w-full pl-2">

                            {/* Avatar Container Upgrade */}
                            <div className="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-colors flex-shrink-0 shadow-inner">
                      <span className="text-xl md:text-2xl font-black tracking-widest drop-shadow-md">
                        {emp.first_name[0] || '?'}{(emp.last_name && emp.last_name[0]) || ''}
                      </span>
                            </div>

                            <div className="flex flex-col gap-2 w-full md:w-[40%] border-b md:border-b-0 md:border-r border-zinc-800/50 pb-4 md:pb-0 md:pr-6">
                              <div className="flex items-center gap-3">
                                <h3 className={`text-xl font-black ${normalizedStatus === 'resigned' ? 'text-zinc-500' : 'text-white'} uppercase tracking-wider drop-shadow-sm`}>{emp.first_name} {emp.mid_name} {emp.last_name}</h3>
                                <span className={`px-2.5 py-1 rounded-lg text-[10px] font-black tracking-[0.1em] uppercase border shadow-inner ${getStatusStyle(emp.status)}`}>
                          {normalizedStatus === 'on-leave' ? 'On Leave' : emp.status}
                        </span>
                              </div>
                              <div className="flex flex-col gap-1 text-xs md:text-sm mt-1">
                                <span className="text-emerald-400 font-bold tracking-widest uppercase">{emp.role}</span>
                                <span className="text-zinc-400 font-medium tracking-widest uppercase">{emp.department}</span>

                                <span className="inline-block px-3 py-1.5 bg-zinc-800/40 border border-zinc-700/50 rounded-lg text-zinc-400 font-bold tracking-widest uppercase mt-2 w-fit text-[10px] shadow-inner">
                          CODE: {emp.employee_code}
                        </span>
                              </div>
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 flex-1 text-xs md:text-sm md:pl-6">
                              <div className="flex flex-col gap-1">
                                <span className="text-zinc-600 font-bold uppercase tracking-widest text-[10px]">Contact</span>
                                <span className="text-zinc-300 font-medium">{emp.phone}</span>
                                <span className="text-zinc-400">{emp.email}</span>
                              </div>
                              <div className="flex flex-col gap-1">
                                <span className="text-zinc-600 font-bold uppercase tracking-widest text-[10px]">Dates</span>
                                <div className="flex flex-col gap-1.5">
                                  <span className="flex items-center justify-between text-zinc-400"><span className="text-zinc-500">DOB:</span> <span className="text-zinc-300 font-medium">{emp.birthdate}</span></span>
                                  <span className="flex items-center justify-between text-zinc-400"><span className="text-zinc-500">Hired:</span> <span className="text-zinc-300 font-medium">{emp.hiredate}</span></span>
                                </div>
                              </div>
                            </div>
                          </div>

                          {/* --- UPGRADED 2x2 ACTION BUTTON GRID --- */}
                          <div className="grid grid-cols-2 gap-2 flex-shrink-0 mt-4 md:mt-0 z-10 pr-2">
                            <button
                                onClick={() => openViewModal(emp)}
                                className="p-3 text-zinc-400 bg-zinc-800/30 hover:bg-emerald-500/10 hover:text-emerald-400 hover:shadow-[0_0_15px_rgba(16,185,129,0.2)] border border-zinc-700/50 hover:border-emerald-500/50 rounded-xl transition-all flex justify-center items-center"
                                title="View Details"
                            >
                              <FileSearch size={20} />
                            </button>
                            <Link
                                to={`/employees/edit/${emp.employee_code}`}
                                className="p-3 text-zinc-400 bg-zinc-800/30 hover:bg-blue-500/10 hover:text-blue-400 hover:shadow-[0_0_15px_rgba(59,130,246,0.2)] border border-zinc-700/50 hover:border-blue-500/50 rounded-xl transition-all flex justify-center items-center"
                                title="Edit Employee"
                            >
                              <Settings size={20} />
                            </Link>

                            {/* Dynamic Toggle Leave Button */}
                            <button
                                onClick={() => toggleLeaveStatus(emp)}
                                disabled={isMarkingLeave === emp.id || normalizedStatus === 'resigned'}
                                className={`p-3 rounded-xl transition-all border flex justify-center items-center ${
                                    normalizedStatus === 'resigned'
                                        ? 'bg-zinc-900/50 text-zinc-700 border-zinc-800/50 cursor-not-allowed'
                                        : normalizedStatus === 'on-leave'
                                            ? 'text-zinc-400 bg-zinc-800/30 border-zinc-700/50 hover:bg-green-500/10 hover:text-green-400 hover:shadow-[0_0_15px_rgba(34,197,94,0.2)] hover:border-green-500/50'
                                            : 'text-zinc-400 bg-zinc-800/30 border-zinc-700/50 hover:bg-yellow-500/10 hover:text-yellow-400 hover:shadow-[0_0_15px_rgba(234,179,8,0.2)] hover:border-yellow-500/50'
                                }`}
                                title={normalizedStatus === 'on-leave' ? "Return to Active" : "Mark as On Leave"}
                            >
                              {isMarkingLeave === emp.id
                                  ? <Loader2 size={20} className="animate-spin" />
                                  : normalizedStatus === 'on-leave'
                                      ? <CalendarCheck size={20} />
                                      : <CalendarMinus size={20} />
                              }
                            </button>

                            <button
                                onClick={() => openResignModal(emp)}
                                disabled={isResigning === emp.id || normalizedStatus === 'resigned'}
                                className={`p-3 rounded-xl transition-all border flex justify-center items-center ${normalizedStatus === 'resigned' ? 'bg-zinc-900/50 text-zinc-700 border-zinc-800/50 cursor-not-allowed' : 'text-zinc-400 bg-zinc-800/30 border-zinc-700/50 hover:bg-red-500/10 hover:text-red-400 hover:shadow-[0_0_15px_rgba(239,68,68,0.2)] hover:border-red-500/50'}`}
                                title="Mark as Resigned"
                            >
                              {isResigning === emp.id ? <Loader2 size={20} className="animate-spin" /> : <UserMinus size={20} />}
                            </button>
                          </div>
                        </div>
                    )}
                  )
              )}
            </div>
          </div>
        </div>

        {/* --- CUSTOM RESIGN MODAL --- */}
        {showResignModal && (
            <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
              <div className="absolute inset-0 bg-black/60 backdrop-blur-sm animate-in fade-in duration-300" onClick={closeResignModal}></div>
              <div className="relative w-full max-w-md bg-[#121212] border border-zinc-800 rounded-[2rem] p-8 shadow-2xl animate-in zoom-in-95 fade-in duration-300">
                <button onClick={closeResignModal} className="absolute top-6 right-6 text-zinc-500 hover:text-white transition-colors">
                  <X size={20} />
                </button>
                <div className="flex flex-col items-center text-center gap-4">
                  <div className="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center border border-red-500/20 mb-2">
                    <AlertTriangle className="text-red-500" size={32} />
                  </div>
                  <h2 className="text-xl font-black text-white uppercase tracking-wider">Confirm Resignation</h2>
                  <p className="text-zinc-400 text-sm font-medium tracking-wide">
                    Are you sure you want to mark <span className="text-white font-bold">{employeeToResign?.first_name} {employeeToResign?.last_name}</span> as resigned? This action will update their employment status.
                  </p>
                  <div className="flex w-full gap-3 mt-6">
                    <button onClick={closeResignModal} className="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-zinc-800 text-zinc-300 hover:bg-zinc-700 transition-all">Cancel</button>
                    <button onClick={confirmResign} className="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-red-600 text-white hover:bg-red-500 shadow-lg shadow-red-600/20 transition-all">Confirm</button>
                  </div>
                </div>
              </div>
            </div>
        )}

        {/* --- CUSTOM VIEW MODAL --- */}
        {showViewModal && employeeToView && (
            <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
              <div className="absolute inset-0 bg-black/80 backdrop-blur-md animate-in fade-in duration-300" onClick={closeViewModal}></div>
              <div className="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-gradient-to-b from-[#121212] to-[#0a0a0a] border border-zinc-800 rounded-[2rem] shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] animate-in zoom-in-95 fade-in duration-300 scrollbar-hide flex flex-col">

                {/* Modal Header */}
                <div className="p-6 md:p-8 border-b border-zinc-800/80 flex justify-between items-start bg-[#121212]/90 sticky top-0 z-10 backdrop-blur-xl">
                  <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
                  <div className="flex flex-col gap-1">
                    <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
                      {employeeToView.first_name} {employeeToView.last_name}
                    </h1>

                    {/* History Button next to the Code */}
                    <div className="flex items-center gap-4 mt-1">
                      <p className="text-emerald-500 font-bold text-sm tracking-widest uppercase">
                        EMPLOYEE CODE: {employeeToView.employee_code}
                      </p>
                      <Link
                          to={`/employees/history/${employeeToView.employee_code}`}
                          className="flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-300 font-bold uppercase tracking-widest bg-blue-500/10 px-3 py-1 rounded-lg border border-blue-500/30 hover:border-blue-400 shadow-inner transition-all"
                      >
                        <History size={14} /> View Edit History
                      </Link>
                    </div>

                  </div>
                  <button onClick={closeViewModal} className="text-zinc-500 hover:text-white transition-colors bg-zinc-900 p-2 rounded-full">
                    <X size={24} />
                  </button>
                </div>

                {isFetchingDetails ? (
                    <div className="flex flex-col items-center justify-center py-32 gap-4">
                      <Loader2 className="text-emerald-500 animate-spin" size={48} />
                      <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Loading Details...</p>
                    </div>
                ) : (
                    <div className="flex flex-col gap-8 p-6 md:p-8">

                      <div className="flex flex-col gap-4">
                        <h2 className="text-teal-500 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800/80 pb-3">
                          User Account
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <ViewField label="Username" value={employeeToView.username} />
                          <ViewField label="Email Address" value={employeeToView.email} />
                          <ViewField label="Password" value="••••••••" />
                          <ViewField label="System Role" value={employeeToView.role === 'admin' ? 'Administrator' : 'Employee'} />
                        </div>
                      </div>

                      <div className="flex flex-col gap-4">
                        <h2 className="text-teal-500 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800/80 pb-3">
                          Employee Details
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                          <ViewField label="First Name" value={employeeToView.first_name} />
                          <ViewField label="Middle Name" value={employeeToView.mid_name} />
                          <ViewField label="Last Name" value={employeeToView.last_name} />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <ViewField label="Phone Number" value={employeeToView.phone} />
                          <ViewField label="Address" value={employeeToView.address} />
                          <ViewField label="Birthdate" value={employeeToView.birthdate} />
                          <ViewField label="Hire Date" value={employeeToView.hiredate} />
                        </div>
                      </div>

                      <div className="flex flex-col gap-4">
                        <h2 className="text-teal-500 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800/80 pb-3">
                          Position & Compensation
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <ViewField label="Department" value={employeeToView.department} />
                          <ViewField label="Position" value={employeeToView.role} />
                          <ViewField label="Employment Type" value={employeeToView.employment_type} />
                          <ViewField label="Basic Salary" value={employeeToView.basic_salary !== '0.00' ? `₱${employeeToView.basic_salary}` : '-'} />
                        </div>
                      </div>

                      {/* Upgraded Attendance Section matching Home.jsx */}
                      <div className="flex flex-col gap-4 mt-2">
                        <button
                            onClick={() => toggleAttendance(employeeToView.id)}
                            className="w-full flex items-center justify-between bg-zinc-900/40 border border-zinc-800/80 hover:border-teal-500/50 hover:bg-zinc-800/80 transition-all rounded-2xl p-5 shadow-inner"
                        >
                          <div className="flex items-center gap-3">
                            <CalendarClock className="text-teal-500" size={22} />
                            <span className="text-sm font-black text-white tracking-[0.1em] uppercase">Recent Attendance</span>
                          </div>
                          {showAttendance ? <ChevronUp className="text-zinc-400" size={20} /> : <ChevronDown className="text-zinc-400" size={20} />}
                        </button>

                        {showAttendance && (
                            <div className="bg-[#0a0a0a]/80 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-5 md:p-6 animate-in slide-in-from-top-2 fade-in duration-300">
                              {isFetchingAttendance ? (
                                  <div className="flex justify-center py-8">
                                    <Loader2 className="text-teal-500 animate-spin" size={32} />
                                  </div>
                              ) : (
                                  <div className="flex flex-col w-full">

                                    {/* Upgraded Table Header */}
                                    <div className="grid grid-cols-4 gap-2 md:gap-4 px-4 py-3 mb-4 bg-zinc-900/40 border border-zinc-800/80 rounded-xl text-[10px] font-black text-zinc-300 uppercase tracking-[0.15em] shadow-inner items-center">
                                      <span className="pl-2">Date</span>
                                      <span>Time In</span>
                                      <span>Time Out</span>
                                      <span>Status</span>
                                    </div>

                                    <div className="flex flex-col gap-3">
                                      {attendanceRecords.length === 0 ? (
                                          <span className="text-zinc-500 text-sm italic px-4">No recent attendance found.</span>
                                      ) : (
                                          attendanceRecords.map((record) => (
                                              <div key={record.id} className="group grid grid-cols-4 gap-2 md:gap-4 items-center bg-[#121212] border border-zinc-800/80 hover:border-teal-500/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(20,184,166,0.15)] transition-all duration-300 rounded-xl p-3 relative overflow-hidden text-xs md:text-sm">

                                                {/* Subtle Left Border Glow */}
                                                <div className={`absolute left-0 top-0 bottom-0 w-1 ${record.status === 'Present' ? 'bg-green-500' : record.status === 'Late' ? 'bg-yellow-500' : 'bg-red-500'} opacity-20 group-hover:opacity-100 transition-opacity`}></div>

                                                <div className="flex items-center gap-3 pl-2">
                                                  <div className="hidden sm:flex w-8 h-8 rounded-lg bg-teal-500/10 items-center justify-center border border-teal-500/20 text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors shadow-inner flex-shrink-0">
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
                                          ))
                                      )}
                                    </div>

                                    <div className="pt-6 mt-6 border-t border-zinc-800/80 flex justify-end">
                                    </div>

                                  </div>
                              )}
                            </div>
                        )}
                      </div>

                    </div>
                )}
              </div>
            </div>
        )}

      </div>
  );
};

export default EmployeeList;