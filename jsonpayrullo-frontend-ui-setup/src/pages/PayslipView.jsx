import React, { useState, useEffect } from 'react';
import { useParams, Link, useLocation } from 'react-router-dom'; // Added useLocation
import { PDFViewer, PDFDownloadLink } from '@react-pdf/renderer';
import { ArrowLeft, Download, Loader2 } from 'lucide-react';
import PayslipDocument from '../components/PayslipDocument';

const PayslipView = () => {
    const { periodId, runId } = useParams();
    const location = useLocation(); // <-- Reads the state passed from the previous page

    const [data, setData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    // Check if the user came from the dashboard
    const fromDashboard = location.state?.from === 'dashboard';

    useEffect(() => {
        const fetchSlip = async () => {
            try {
                const res = await fetch(
                    `http://localhost/JSONPayrullo/Payrolls/getSlip/${periodId}/${runId}`,
                    { headers: { 'Accept': 'application/json' }, credentials: 'include' }
                );
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    console.log(result.run);
                    setData(result);
                } else {
                    setError(result.response || 'Failed to load payslip.');
                }
            } catch {
                setError('Network error. Is XAMPP running?');
            } finally {
                setIsLoading(false);
            }
        };
        fetchSlip();
    }, [periodId, runId]);

    if (isLoading) return (
        <div className="flex items-center justify-center min-h-screen bg-[#0a0a0a]">
            <Loader2 className="text-violet-500 animate-spin" size={48} />
        </div>
    );

    if (error) return (
        <div className="flex items-center justify-center min-h-screen bg-[#0a0a0a] text-red-400 font-bold uppercase tracking-widest">
            {error}
        </div>
    );

    const { run, allowances, deductions, period } = data;
    const fileName = `payslip-${run.employee_code}-${period.period_start}.pdf`;

    return (
        <div className="flex flex-col min-h-screen bg-[#0a0a0a] p-6">

            {/* Top Bar */}
            <div className="flex justify-between items-center mb-6 max-w-5xl mx-auto w-full">
                {/* UPDATED: Dynamic Link and Text */}
                <Link
                    to={fromDashboard ? '/' : `/payroll/${periodId}`}
                    className="flex items-center gap-2 text-zinc-400 hover:text-white transition-colors text-sm font-bold uppercase tracking-wider"
                >
                    <ArrowLeft size={16} /> {fromDashboard ? 'Back to Dashboard' : 'Back to Period'}
                </Link>

                <PDFDownloadLink
                    document={
                        <PayslipDocument
                            run={run}
                            allowances={allowances}
                            deductions={deductions}
                            period={period}
                        />
                    }
                    fileName={fileName}
                    className="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm uppercase tracking-wider transition-colors shadow-lg shadow-violet-600/20"
                >
                    {({ loading }) => loading
                        ? <><Loader2 size={16} className="animate-spin" /> Preparing...</>
                        : <><Download size={16} /> Download PDF</>
                    }
                </PDFDownloadLink>
            </div>

            {/* PDF Preview */}
            <div
                className="flex-1 max-w-5xl mx-auto w-full rounded-2xl overflow-hidden border border-zinc-800 shadow-2xl"
                style={{ minHeight: '80vh' }}
            >
                <PDFViewer width="100%" height="100%" style={{ border: 'none', minHeight: '80vh' }}>
                    <PayslipDocument
                        run={run}
                        allowances={allowances}
                        deductions={deductions}
                        period={period}
                    />
                </PDFViewer>
            </div>

        </div>
    );
};

export default PayslipView;