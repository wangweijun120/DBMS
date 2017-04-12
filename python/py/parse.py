#!D:\python\python.exe
import sys
import io
import getopt

from pdfminer.pdfinterp import PDFResourceManager, process_pdf
from pdfminer.converter import HTMLConverter
from pdfminer.layout import LAParams
from pdfminer.utils import set_debug_logging


def parse(in_stream,out_stream):
    debug = False
    # input option
    password = ''
    pagenos = set()
    maxpages = 0
    # output option
    outfile = None
    outtype = None
    outdir = None
    layoutmode = 'normal'
    codec = 'utf-8'
    pageno = 1
    scale = 1
    caching = True
    showpageno = True
    laparams = LAParams()
    if debug:
        set_debug_logging()
    rsrcmgr = PDFResourceManager(caching=caching)
    outfp = io.open(out_stream, 'wt', encoding=codec, errors='ignore')
    device = HTMLConverter(rsrcmgr, outfp, scale=scale, layoutmode=layoutmode,
                               laparams=laparams, outdir=outdir, debug=debug)

    fp = io.open(in_stream, 'rb')
    process_pdf(rsrcmgr, device, fp, pagenos, maxpages=maxpages, password=password,
                    caching=caching, check_extractable=True)
    fp.close()
    device.close()
    outfp.close()
    return out_stream;
