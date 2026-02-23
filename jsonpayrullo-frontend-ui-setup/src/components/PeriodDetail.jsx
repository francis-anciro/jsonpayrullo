import React, { useState, useEffect } from 'react';
import { ArrowLeft, Loader2, Play, Settings, FileText, X, PlusCircle, CheckCircle2 } from 'lucide-react';
import { Link, useParams } from 'react-router-dom';

const PeriodDetail = () => {
  const { id } = useParams();
  const [isMounted, setIsMounted] = useState(false);
  
  // Period Info State
  const [periodInfo, setPeriodInfo] = useState({ status: 'open' }); // 'open', 'processed', 'released'
  const [isGenerating, setIsGenerating] = useState(false);
  const [payrollRuns, setPayrollRuns] = useState([]);
  
  // Manage Modal State
  const [showManageModal, setShowManageModal] = useState(false);
  const [activeEmp, setActiveEmp] = useState(null);
  
  // Form States for Allowances/Deductions inside Modal
  const [newAllowance, setNewAllowance] = useState({ name: '', amount: '' });
  const [newDeduction, setNewDeduction] = useState({ name: '', amount: '' });

  useEffect(() => {
    setIsMounted(true);
    // Simulate initial fetch of Period Data
    setPeriodInfo({
      id: id,
      period_string: 'Feb 1 – Feb 15, 2026',
      pay_date: 'Feb 20, 2026',
      status: 'open' // Change to 'processed' to see the release button
    });
  }, [id]);

  const handleGeneratePayroll = async () => {
    setIsGenerating(true);
    try {
      // TODO: BACKEND READY - Trigger backend payroll engine
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      setPayrollRuns([
        { 
          id: 101, emp_name: 'John Doe', code: 'EMP-001', dept: 'Human Resources', role: 'HR Manager',
          basic: 37500, ot: 0, allowances: [{ name: 'Rice Subsidy', amount: 1500 }], 
          deductions: [{ name: 'Tax', amount: 3000 }, { name: 'SSS', amount: 500 }],
          gross: 39000, net: 35500
        },
        { 
          id: 102, emp_name: 'Jane Smith', code: 'EMP-002', dept: 'IT', role: 'Software Engineer',
          basic: 45000, ot: 2500, allowances: [], 
          deductions: [{ name: 'Tax', amount: 4500 }],
          gross: 47500, net: 43000
        }
      ]);
      setPeriodInfo(prev => ({ ...prev, status: 'processed' }));
    } catch (error) {
      console.error(error);
    } finally {
      setIsGenerating(false);
    }
  };

  const openManageModal = (emp) => {
    setActiveEmp({ ...emp }); // Clone to edit
    setShowManageModal(true);
  };

  // Add Allowance/Deduction Logic (Local State for now)
  const addAllowance = () => {
    if (!newAllowance.name || !newAllowance.amount) return;
    setActiveEmp(prev => ({
      ...prev,
      allowances: [...prev.allowances, { name: newAllowance.name, amount: parseFloat(newAllowance.amount) }]
    }));
    setNewAllowance({ name: '', amount: '' });
  };

  const addDeduction = () => {
    if (!newDeduction.name || !newDeduction.amount) return;
    setActiveEmp(prev => ({
      ...prev,
      deductions: [...prev.deductions, { name: newDeduction.name, amount: parseFloat(newDeduction.amount) }]
    }));
    setNewDeduction({ name: '', amount: '' });
  };

  // Live Recalculation inside Modal
  const calcGross = () => activeEmp.basic + activeEmp.ot + activeEmp.allowances.reduce((acc, curr) => acc + curr.amount, 0);
  const calcTotalDeductions = () => activeEmp.deductions.reduce((acc, curr) => acc + curr.amount, 0);
  const calcNet = () => calcGross() - calcTotalDeductions();

  return (
    <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">
      
      {/* --- BACKGROUND LAYER --- */}
      <div 
        className={`fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50`}
        style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}
      ></div>
      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      <div className={`relative z-10 w-full max-w-7xl flex flex-col gap-6 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>
        
        {/* HEADER */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
          <div className="flex items-center gap-4">
            <Link to="/payroll" className="p-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors bg-[#121212] border border-zinc-800 shadow-lg">
              <ArrowLeft size={24} />
            </Link>
            <div className="flex flex-col">
              <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
                Period Detail
              </h1>
              <p className="text-violet-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
                {periodInfo.period_string} | Pay Date: {periodInfo.pay_date}
              </p>
            </div>
          </div>

          <div className="flex gap-3">
            <span className={`px-4 py-2 rounded-xl text-xs font-bold tracking-widest uppercase border flex items-center justify-center
              ${periodInfo.status === 'open' ? 'border-blue-500 text-blue-500 bg-blue-500/10' : 
                periodInfo.status === 'processed' ? 'border-yellow-500 text-yellow-500 bg-yellow-500/10' : 'border-green-500 text-green-500 bg-green-500/10'}`}>
              STATUS: {periodInfo.status}
            </span>

            {periodInfo.status === 'open' && (
              <button onClick={handleGeneratePayroll} disabled={isGenerating} className="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-lg shadow-violet-600/20">
                {isGenerating ? <Loader2 className="animate-spin" size={18} /> : <Play size={18} />} Generate Payroll
              </button>
            )}
          </div>
        </div>

        {/* DATA TABLE */}
        <div className="bg-[#0a0a0a]/80 backdrop-blur-sm border border-zinc-700 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">
          {payrollRuns.length === 0 && !isGenerating ? (
             <div className="flex flex-col items-center justify-center flex-1 gap-4 py-20 text-zinc-500">
               <FileText size={48} className="opacity-50" />
               <p className="font-bold tracking-widest uppercase text-sm">Click Generate to process employee payrolls</p>
             </div>
          ) : isGenerating ? (
             <div className="flex flex-col items-center justify-center flex-1 gap-4 py-20">
               <Loader2 className="text-violet-500 animate-spin" size={48} />
               <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Calculating Algorithms...</p>
             </div>
          ) : (
            <div className="w-full overflow-x-auto scrollbar-hide">
              <div className="min-w-[1000px]">
                
                {/* --- UPGRADED TABLE HEADER --- */}
                <div className="grid grid-cols-9 gap-4 px-4 py-4 mb-4 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl text-xs font-black text-zinc-300 uppercase tracking-[0.15em] shadow-inner items-center">
                  <span className="col-span-2 pl-2">Employee</span>
                  <span className="col-span-2">Department / Role</span>
                  <span>Basic Pay</span>
                  <span>Net Pay</span>
                  <span className="col-span-3 text-right pr-4">Actions</span>
                </div>

                <div className="flex flex-col mt-4 gap-3">
                  {payrollRuns.map(run => (
                    <div key={run.id} className="grid grid-cols-9 gap-4 items-center bg-[#121212] border border-zinc-800 p-4 rounded-xl hover:border-violet-500/30 transition-all">
                      <div className="col-span-2 flex flex-col">
                        <span className="font-bold text-white truncate">{run.emp_name}</span>
                        <span className="text-xs text-zinc-500 uppercase">{run.code}</span>
                      </div>
                      <div className="col-span-2 flex flex-col">
                        <span className="text-sm text-zinc-300 truncate">{run.dept}</span>
                        <span className="text-xs text-violet-400 uppercase truncate">{run.role}</span>
                      </div>
                      <span className="text-zinc-300 font-medium">₱{run.basic.toLocaleString()}</span>
                      <span className="text-green-400 font-bold">₱{run.net.toLocaleString()}</span>
                      
                      <div className="col-span-3 flex justify-end gap-2">
                        <button onClick={() => openManageModal(run)} className="flex items-center gap-2 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                          <Settings size={14} /> Manage
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}
        </div>

        {/* BOTTOM RELEASE BUTTON */}
        {periodInfo.status === 'processed' && payrollRuns.length > 0 && (
           <div className="flex justify-end mt-4 animate-in fade-in slide-in-from-bottom-4">
             <button className="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-8 py-4 rounded-xl font-black text-sm tracking-[0.2em] uppercase transition-colors shadow-[0_0_20px_rgba(22,163,74,0.3)]">
               <CheckCircle2 size={20} /> Release Payroll
             </button>
           </div>
        )}

      </div>

      {/* --- MANAGE MODAL --- */}
      {showManageModal && activeEmp && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/80 backdrop-blur-md animate-in fade-in duration-300" onClick={() => setShowManageModal(false)}></div>
          <div className="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-[#0a0a0a] border border-zinc-800 rounded-[2rem] shadow-2xl animate-in zoom-in-95 fade-in duration-300 scrollbar-hide flex flex-col">
            
            {/* Header */}
            <div className="p-6 md:p-8 border-b border-zinc-800 flex justify-between items-center bg-[#121212] sticky top-0 z-10">
              <div className="flex flex-col">
                <h2 className="text-2xl font-black text-white uppercase tracking-wider">{activeEmp.emp_name}</h2>
                <p className="text-violet-400 font-bold text-xs tracking-widest uppercase">{activeEmp.code} | {activeEmp.role}</p>
              </div>
              <button onClick={() => setShowManageModal(false)} className="text-zinc-500 hover:text-white transition-colors p-2 bg-zinc-900 rounded-full">
                <X size={24} />
              </button>
            </div>

            {/* Split Content */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
              
              {/* LEFT: ALLOWANCES */}
              <div className="flex flex-col gap-4">
                <h3 className="text-sm font-bold text-zinc-400 uppercase tracking-widest border-b border-zinc-800 pb-2">Allowances</h3>
                <div className="flex gap-2">
                  <select className="flex-1 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none focus:border-violet-500" value={newAllowance.name} onChange={e => setNewAllowance({...newAllowance, name: e.target.value})}>
                    <option value="">Select...</option>
                    <option value="Rice Subsidy">Rice Subsidy</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Performance Bonus">Performance Bonus</option>
                  </select>
                  <input type="number" placeholder="Amt" className="w-24 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none" value={newAllowance.amount} onChange={e => setNewAllowance({...newAllowance, amount: e.target.value})} />
                  <button onClick={addAllowance} className="bg-violet-600 hover:bg-violet-500 text-white p-2 rounded-lg"><PlusCircle size={20}/></button>
                </div>
                <div className="flex flex-col gap-2 mt-2">
                  {activeEmp.allowances.map((al, idx) => (
                    <div key={idx} className="flex justify-between items-center bg-[#121212] p-3 rounded-lg border border-zinc-800/50">
                      <span className="text-sm text-zinc-300">{al.name}</span>
                      <span className="text-sm font-bold text-white">+ ₱{al.amount.toLocaleString()}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* RIGHT: DEDUCTIONS */}
              <div className="flex flex-col gap-4">
                <h3 className="text-sm font-bold text-zinc-400 uppercase tracking-widest border-b border-zinc-800 pb-2">Deductions</h3>
                <div className="flex gap-2">
                  <select className="flex-1 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none focus:border-red-500" value={newDeduction.name} onChange={e => setNewDeduction({...newDeduction, name: e.target.value})}>
                    <option value="">Select...</option>
                    <option value="Tax">Withholding Tax</option>
                    <option value="SSS">SSS Contribution</option>
                    <option value="PhilHealth">PhilHealth</option>
                    <option value="Pag-IBIG">Pag-IBIG</option>
                  </select>
                  <input type="number" placeholder="Amt" className="w-24 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none" value={newDeduction.amount} onChange={e => setNewDeduction({...newDeduction, amount: e.target.value})} />
                  <button onClick={addDeduction} className="bg-red-600 hover:bg-red-500 text-white p-2 rounded-lg"><PlusCircle size={20}/></button>
                </div>
                <div className="flex flex-col gap-2 mt-2">
                  {activeEmp.deductions.map((ded, idx) => (
                    <div key={idx} className="flex justify-between items-center bg-[#121212] p-3 rounded-lg border border-red-900/20">
                      <span className="text-sm text-zinc-300">{ded.name}</span>
                      <span className="text-sm font-bold text-red-400">- ₱{ded.amount.toLocaleString()}</span>
                    </div>
                  ))}
                </div>
              </div>

            </div>

            {/* Footer Calculations & Payslip Button */}
            <div className="mt-auto p-6 md:p-8 bg-[#121212] border-t border-zinc-800 grid grid-cols-1 md:grid-cols-2 items-end gap-6">
              
              <div className="flex flex-col gap-2 bg-[#0a0a0a] p-4 rounded-xl border border-zinc-800">
                <div className="flex justify-between text-xs font-bold text-zinc-500 uppercase tracking-widest">
                  <span>Gross Pay</span>
                  <span className="text-white">₱{calcGross().toLocaleString()}</span>
                </div>
                <div className="flex justify-between text-xs font-bold text-red-500 uppercase tracking-widest">
                  <span>Total Deductions</span>
                  <span>- ₱{calcTotalDeductions().toLocaleString()}</span>
                </div>
                <div className="h-px w-full bg-zinc-800 my-1"></div>
                <div className="flex justify-between text-sm font-black text-green-400 uppercase tracking-widest">
                  <span>Net Pay</span>
                  <span className="text-lg">₱{calcNet().toLocaleString()}</span>
                </div>
              </div>

              <div className="flex justify-end h-full">
                 <Link to={`/payroll/payslip/${periodInfo.id}/${activeEmp.id}`} className="flex items-center justify-center gap-2 bg-violet-600/10 border border-violet-500/50 hover:bg-violet-600 hover:text-white text-violet-400 px-6 py-4 rounded-xl font-bold tracking-widest text-xs uppercase transition-all w-full md:w-auto h-full">
                   <FileText size={18} /> Generate Payslip
                 </Link>
              </div>

            </div>
          </div>
        </div>
      )}

    </div>
  );
};

export default PeriodDetail;