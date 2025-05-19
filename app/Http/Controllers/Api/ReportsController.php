<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\IndexReportRequest;
use App\Http\Resources\ReportResource;
use App\Services\ReportsService;

class ReportsController extends Controller
{
  protected $reportService;

  public function __construct(ReportsService $reportService)
  {
    $this->reportService = $reportService;
  }

  public function index(IndexReportRequest $request)
  {
    $filteredReports = $this->reportService->getFilteredReports($request->input('dates'));

    $payload = $filteredReports->flatMap(function ($report) {
      return [new ReportResource($report)];
    });

    return $payload;
  }
}