<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WebhookController extends Controller
{
    protected $repoPath = "/home/baanusbistrooo/public_html";

    /**
     * Show the git pull output page.
     */
    public function handle(Request $request, $branch = 'main')
    {
        Log::info('Webhook Payload:', $request->all());

        $output = [];
        exec("cd {$this->repoPath} && git pull origin {$branch} 2>&1", $output);

        $stats = [
            'commits' => 0,
            'filesChanged' => 0,
            'insertions' => 0,
            'deletions' => 0,
        ];

        // Parse commit range (old..new)
        $oldCommit = null;
        $newCommit = null;
        foreach ($output as $line) {
            if (preg_match('/([0-9a-f]+)\.\.([0-9a-f]+)\s+' . preg_quote($branch, '/') . '/i', $line, $matches)) {
                $oldCommit = $matches[1];
                $newCommit = $matches[2];
                break;
            }
        }

        // Count commits in the range if found
        if ($oldCommit && $newCommit) {
            $commitsCountOutput = [];
            exec("cd {$this->repoPath} && git rev-list --count {$oldCommit}..{$newCommit}", $commitsCountOutput);
            $stats['commits'] = isset($commitsCountOutput[0]) ? (int) $commitsCountOutput[0] : 0;
        } else {
            // fallback commit count if pull output present
            $stats['commits'] = count($output) > 0 ? 1 : 0;
        }

        // Parse files changed, insertions, deletions
        foreach ($output as $line) {
            if (preg_match('/(\d+) files? changed/', $line, $matches)) {
                $stats['filesChanged'] = (int) $matches[1];
            }
            if (preg_match('/(\d+) insertions?\(\+\)/', $line, $matches)) {
                $stats['insertions'] = (int) $matches[1];
            }
            if (preg_match('/(\d+) deletions?\(-\)/', $line, $matches)) {
                $stats['deletions'] = (int) $matches[1];
            }
        }

        return view('webhook.output', [
            'branch' => $branch,
            'output' => $output,
            'stats' => $stats,
        ]);
    }

    /**
     * AJAX handler to run git pull again and return JSON.
     */
    public function pullAgain(Request $request, $branch = 'main')
    {
        $output = [];
        exec("cd {$this->repoPath} && git pull origin {$branch} 2>&1", $output);

        return response()->json([
            'message' => 'Pull executed successfully!',
            'output' => $output,
        ]);
    }

    /**
     * Download the git pull output as a text file.
     */
    public function downloadOutput($branch = 'main')
    {
        $output = [];
        exec("cd {$this->repoPath} && git pull origin {$branch} 2>&1", $output);

        $fileName = "git_pull_output_{$branch}_" . date('Y-m-d_H-i-s') . ".txt";

        return new StreamedResponse(function () use ($output) {
            $handle = fopen('php://output', 'w');
            foreach ($output as $line) {
                fwrite($handle, $line . PHP_EOL);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
