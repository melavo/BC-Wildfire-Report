<a href="javascript:void(0);"
    class="link-underline"
    data-bs-toggle="modal"
    data-bs-target="#modal-template"
    modal-content="
        <table class='table table-striped'>
        <tr>
            <td class='align-middle text-nowrap'>Title</td>
            <td class='align-middle text-nowrap'>{{ isset($fireItem['INCIDENT_NAME']) ? $fireItem['INCIDENT_NAME'] : '' }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Number</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_NUMBER'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Ignition Date</td>
            <td class='align-middle text-nowrap'>{{ \Carbon\Carbon::parse($fireItem['IGNITION_DATE'])->format('M, d Y') }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Fire Out Date</td>
            <td class='align-middle text-nowrap'>{{ \Carbon\Carbon::parse($fireItem['FIRE_OUT_DATE'])->format('M, d Y') }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Year</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_YEAR'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Status</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_STATUS'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Cause</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_CAUSE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Type Desc</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['RESPONSE_TYPE_DESC'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Fire Centre</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_CENTRE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Zone</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['ZONE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Fire ID</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_ID'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Fire Type</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FIRE_TYPE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Geographical Description</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['GEOGRAPHIC_DESCRIPTION'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Current Size</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['CURRENT_SIZE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Feature Code</td>
            <td class='align-middle text-nowrap'>{{ $fireItem['FEATURE_CODE'] }}</td>
        </tr>
        <tr>
            <td class='align-middle text-nowrap'>Fire URL</td>
            <td class='align-middle text-nowrap'><a href='{{ $fireItem['FIRE_URL'] }}' target='_blank' class='link-underline'>Visit URL</a></td>
        </tr>
        </table>
    ">
    {{ $fireItem['FIRE_NUMBER'] }}
</a>
