import React, { useState, useEffect } from 'react';
import { Plus, Eye, Loader2, CalendarPlus, X, Calendar, ArrowRight, Wallet } from 'lucide-react';
import { Link } from 'react-router-dom';

const Payroll = () => {
  const [isMounted, setIsMounted] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [periods, setPeriods] = useState([]);
  
  // Modal State
  const [showNewModal, setShowNewModal] = useState(false);
  const [newPeriod, setNewPeriod] = useState({ start_date: '', end_date: '', pay_date: '' });
  const [isCreating, setIsCreating] = useState(false);

  useEffect(() => {
    setIsMounted(true);
    fetchPeriods();
  }, []);

  const fetchPeriods = async () => {
    setIsLoading(true);
    try {
      // TODO: BACKEND FETCH READY
      // const res = await fetch('/api/payroll/periods');
      // const data = await res.json();
      // setPeriods(data);

      await new Promise(resolve => setTimeout(resolve, 600)); // Simulate delay
      
      setPeriods([
        { id: 1, period_string: 'Feb 1 – Feb 15, 2026', pay_date: 'Feb 20, 2026', employee_count: 0, status: 'open' },
        { id: 2, period_string: 'Jan 16 – Jan 31, 2026', pay_date: 'Feb 5, 2026', employee_count: 45, status: 'processed' },
        { id: 3, period_string: 'Jan 1 – Jan 15, 2026', pay_date: 'Jan 20, 2026', employee_count: 44, status: 'released' },
      ]);
    } catch (error) {
      console.error("Fetch error:", error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleCreatePeriod = async (e) => {
    e.preventDefault();
    setIsCreating(true);
    try {
      // TODO: BACKEND POST READY
      // await fetch('/api/payroll/periods', { method: 'POST', body: JSON.stringify(newPeriod) });
      await new Promise(resolve => setTimeout(resolve, 800));
      
      setShowNewModal(false);
      fetchPeriods(); // Refresh list after creation
    } catch (error) {
      console.error("Create error:", error);
    } finally {
      setIsCreating(false);
    }
  };

  const getStatusStyle = (status) => {
    switch (status) {
      case 'open': return 'border-blue-500 text-blue-500 bg-blue-500/10';
      case 'processed': return 'border-yellow-500 text-yellow-500 bg-yellow-500/10';
      case 'released': return 'border-green-500 text-green-500 bg-green-500/10';
      default: return 'border-zinc-500 text-zinc-500 bg-zinc-500/10';
    }
  };

  return (
    <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">
      
      {/* --- BACKGROUND LAYER --- */}
      <div 
        className={`fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50`}
        style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}
      ></div>
      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
      <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      <div className={`relative z-10 w-full max-w-5xl flex flex-col gap-6 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>
        
        {/* HEADER */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
          <div className="flex flex-col">
            <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg flex items-center gap-3">
              <Wallet className="text-violet-500" size={32} /> Payroll
            </h1>
            <p className="text-violet-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
              Total Periods: {periods.length}
            </p>
          </div>
          <button 
            onClick={() => setShowNewModal(true)}
            className="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-lg shadow-violet-600/20 border border-violet-500"
          >
            <CalendarPlus size={18} /> New Period
          </button>
        </div>

        {/* --- UPGRADED LIST CONTAINER --- */}
        <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2.5rem] p-6 md:p-8 flex flex-col min-h-[400px] relative overflow-hidden">
          
          {/* Subtle top edge glow for depth */}
          <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-violet-500/20 to-transparent"></div>

          {isLoading ? (
            <div className="flex flex-col items-center justify-center flex-1 gap-4 py-20">
              <Loader2 className="text-violet-500 animate-spin" size={48} />
              <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Loading Periods...</p>
            </div>
          ) : (
            <div className="flex flex-col w-full overflow-x-auto">
              <div className="min-w-[700px]">
                
                {/* --- UPGRADED TABLE HEADER --- */}
                <div className="grid grid-cols-6 gap-4 px-4 py-4 mb-4 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl text-xs font-black text-zinc-300 uppercase tracking-[0.15em] shadow-inner items-center">
                  <span className="col-span-2 pl-2">Payroll Period</span>
                  <span>Pay Date</span>
                  <span>Employees</span>
                  <span>Status</span>
                  <span className="text-right pr-4">Action</span>
                </div>
                
                {/* --- UPGRADED TABLE ROWS ("FIRE" DESIGN) --- */}
                <div className="flex flex-col gap-3">
                  {periods.length === 0 ? (
                    <div className="text-center py-10 text-zinc-500 font-bold uppercase tracking-widest">No payroll periods found.</div>
                  ) : (
                    periods.map((period) => (
                      <div key={period.id} className="group grid grid-cols-6 gap-4 items-center bg-[#121212] border border-zinc-800/80 hover:border-violet-500/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(139,92,246,0.15)] transition-all duration-300 rounded-2xl p-4 relative overflow-hidden">
                        
                        {/* Subtle Left Border Glow based on status */}
                        <div className={`absolute left-0 top-0 bottom-0 w-1 ${period.status === 'open' ? 'bg-blue-500' : period.status === 'processed' ? 'bg-yellow-500' : 'bg-green-500'} opacity-20 group-hover:opacity-100 transition-opacity`}></div>

                        <div className="col-span-2 flex items-center gap-4 pl-2">
                          <div className="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center border border-violet-500/20 text-violet-400 group-hover:bg-violet-500 group-hover:text-white transition-colors shadow-inner">
                            <Calendar size={20} />
                          </div>
                          <div className="flex flex-col">
                            <span className="font-black text-white tracking-wider text-[15px] drop-shadow-sm">{period.period_string}</span>
                            <span className="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Timeframe</span>
                          </div>
                        </div>
                        
                        <div className="flex flex-col">
                          <span className="text-zinc-300 font-bold text-sm bg-zinc-800/40 border border-zinc-700/50 rounded-lg px-3 py-1.5 w-max tracking-wide shadow-inner">{period.pay_date}</span>
                        </div>
                        
                        <div className="flex flex-col justify-center">
                          <div className="flex items-baseline gap-1.5">
                            <span className="text-white font-black text-xl drop-shadow-md">{period.employee_count}</span>
                            <span className="text-zinc-500 text-[10px] font-bold uppercase tracking-widest">Processed</span>
                          </div>
                        </div>
                        
                        <div className="flex items-center">
                          <span className={`px-3 py-1.5 rounded-lg text-[10px] font-black tracking-[0.1em] uppercase border shadow-inner ${getStatusStyle(period.status)}`}>
                            {period.status}
                          </span>
                        </div>
                        
                        <div className="flex justify-end pr-2">
                          <Link 
                            to={`/payroll/${period.id}`}
                            className="px-4 py-2 text-zinc-400 bg-zinc-800/30 hover:bg-violet-600 hover:text-white hover:shadow-[0_0_15px_rgba(139,92,246,0.4)] border border-zinc-700/50 hover:border-violet-500 rounded-xl transition-all flex items-center gap-2 group/btn"
                          >
                            <span className="text-xs font-black uppercase tracking-widest">View</span>
                            <ArrowRight size={14} className="group-hover/btn:translate-x-1 transition-transform" />
                          </Link>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* --- NEW PERIOD MODAL --- */}
      {showNewModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/60 backdrop-blur-sm animate-in fade-in duration-300" onClick={() => setShowNewModal(false)}></div>
          <div className="relative w-full max-w-md bg-[#121212] border border-zinc-800 rounded-[2rem] p-8 shadow-2xl animate-in zoom-in-95 fade-in duration-300">
            <button onClick={() => setShowNewModal(false)} className="absolute top-6 right-6 text-zinc-500 hover:text-white transition-colors">
              <X size={20} />
            </button>
            
            <h2 className="text-xl font-black text-white uppercase tracking-wider mb-6 flex items-center gap-3">
              <CalendarPlus className="text-violet-500" /> Create Period
            </h2>

            <form onSubmit={handleCreatePeriod} className="flex flex-col gap-5">
              <div className="flex flex-col gap-2">
                <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">Period Start</label>
                <input type="date" required className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white" 
                  value={newPeriod.start_date} onChange={(e) => setNewPeriod({...newPeriod, start_date: e.target.value})} />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">Period End</label>
                <input type="date" required className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white" 
                  value={newPeriod.end_date} onChange={(e) => setNewPeriod({...newPeriod, end_date: e.target.value})} />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">Pay Date</label>
                <input type="date" required className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white" 
                  value={newPeriod.pay_date} onChange={(e) => setNewPeriod({...newPeriod, pay_date: e.target.value})} />
              </div>

              <div className="flex gap-3 mt-4">
                <button type="button" onClick={() => setShowNewModal(false)} className="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-zinc-800 text-zinc-300 hover:bg-zinc-700">Cancel</button>
                <button type="submit" disabled={isCreating} className="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-violet-600 text-white hover:bg-violet-500">
                  {isCreating ? 'Creating...' : 'Create'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Payroll;